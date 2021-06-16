<?php namespace Gvera\Controllers;

use Exception;
use Gvera\Exceptions\InvalidCSRFException;
use Gvera\Exceptions\InvalidHttpMethodException;
use Gvera\Exceptions\InvalidMethodException;
use Gvera\Exceptions\InvalidViewException;
use Gvera\Exceptions\NotAllowedException;
use Gvera\Helpers\dependencyInjection\DIContainer;
use Gvera\Helpers\http\HttpRequest;
use Gvera\Helpers\http\HttpResponse;
use Gvera\Helpers\http\JSONResponse;
use Gvera\Helpers\http\PrintErrorResponse;
use Gvera\Helpers\http\Response;
use Gvera\Helpers\locale\Locale;
use Gvera\Helpers\security\BasicAuthenticationStrategy;
use Gvera\Helpers\security\CSRFToken;
use Gvera\Helpers\security\JWTTokenAuthenticationStrategy;
use Gvera\Helpers\security\SessionAuthenticationStrategy;
use Gvera\Helpers\security\AuthenticationContext;
use Gvera\Models\User;
use Gvera\Services\TwigService;
use ReflectionException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class GvController
 * @category Class
 * @package Gvera\Controllers
 * @author    Guido Vera
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.github.com/veraguido/gv
 * Base controller to be used as a parent of all controllers, manages http objects,
 * and the responsibility of loading twig or not.
 */
abstract class GvController
{

    private ?string $method;
    private ?string $name;
    private Environment $twig;
    protected array $viewParams = array();
    protected HttpResponse $httpResponse;
    protected HttpRequest $httpRequest;
    protected DIContainer $diContainer;
    protected bool $protectedController = false;

    const DEFAULT_CONTROLLER = "Index";
    const DEFAULT_METHOD = 'index';
    private TwigService $twigService;

    /**
     * GvController constructor.
     * @param DIContainer $diContainer
     * @param $controllerName
     * @param $method
     * @throws InvalidMethodException
     * @throws ReflectionException
     */
    public function __construct(DIContainer $diContainer, $controllerName, $method)
    {
        $this->diContainer = $diContainer;
        $this->method = $method;
        $this->name = $controllerName;
        $this->httpRequest = $this->diContainer->get('httpRequest');
        $this->httpResponse = $this->diContainer->get('httpResponse');
        $this->twigService = $this->diContainer->get('twigService');

        if (!method_exists($this, $method)) {
            throw new InvalidMethodException(
                'the method was not found on the controller',
                [
                    'method' => $method,
                    'controller' => $controllerName
                ]
            );
        }
    }

    /**
     * @param array $allowedHttpMethods
     * @throws InvalidHttpMethodException
     * @throws InvalidViewException
     * @throws LoaderError
     * @throws NotAllowedException
     * @throws ReflectionException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function init($allowedHttpMethods = []):void
    {
        $this->preInit($allowedHttpMethods);

        $methodName = $this->method;
        $this->$methodName();

        $this->postInit();
    }

    /**
     * @param $allowedHttpMethods
     * @throws InvalidHttpMethodException
     * @throws ReflectionException
     * @throws NotAllowedException
     */
    protected function preInit($allowedHttpMethods)
    {
        $this->checkIfPassIsGranted();
        $annotationUtil = $this->diContainer->get('annotationUtil');
        $isHttpMethodValid = $annotationUtil->validateMethods(
            $allowedHttpMethods,
            $this->httpRequest
        );

        if (false === $isHttpMethodValid) {
            throw new InvalidHttpMethodException(
                'The http method used for this action is not supported',
                [
                    "httpMethod" => $this->httpRequest->getRequestType(),
                    "allowedMethods" => $allowedHttpMethods
                ]
            );
        }

        if ($this->twigService->needsTwig($this->name, $this->method)) {
            $this->twig = $this->twigService->loadTwig();
        }
    }

    /**
     * @throws InvalidViewException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function postInit()
    {
        if ($this->twigService->needsTwig($this->name, $this->method)) {
            $this->httpResponse->response(
                new Response($this->twigService->render($this->name, $this->method, $this->viewParams))
            );
            return;
        }

        if (count($this->viewParams) > 0) {
            throw new InvalidViewException(
                'view params was set, but view could not be found ',
                ['method' => $this->method,'controller ' => $this->name]
            );
        }
    }

    /**
     * @throws NotAllowedException
     */
    public function checkIfPassIsGranted()
    {
        if (!$this->protectedController) {
            return;
        }

        if (null !== $this->httpRequest->getAuthDetails()) {
            $this->mustPassBasicAuthentication();
            return;
        }

        if (null !== $this->httpRequest->getBearerToken()) {
            $this->mustPassTokenAuthentication();
        }

        $this->mustPassSessionAuthentication();
    }

    /**
     * @throws NotAllowedException
     */
    protected function mustPassSessionAuthentication()
    {
        $sessionStrategy = new SessionAuthenticationStrategy(
            $this->getSession(),
            $this->getUserService(),
            $this->getEntityManager()
        );
        $context =new AuthenticationContext($sessionStrategy);
        if (!$context->isUserLoggedIn()) {
            throw new NotAllowedException(Locale::getLocale('user is not allowed'));
        }
    }

    /**
     * @throws NotAllowedException
     */
    protected function mustPassBasicAuthentication()
    {
        $basicAuthStrategy = new BasicAuthenticationStrategy(
            $this->getEntityManager(),
            $this->getUserService(),
            $this->httpRequest->getAuthDetails()
        );
        $context = new AuthenticationContext($basicAuthStrategy);
        if (!$context->isUserLoggedIn()) {
            throw new NotAllowedException(Locale::getLocale('user is not allowed'));
        }
    }

    /**
     * @throws NotAllowedException
     * @throws Exception
     */
    public function mustPassTokenAuthentication()
    {
        $token = $this->httpRequest->getBearerToken();
        $jwtTokenStrategy = new JWTTokenAuthenticationStrategy($token, $this->getEntityManager());
        $context = new AuthenticationContext($jwtTokenStrategy);
        if (!$context->isUserLoggedIn()) {
            throw new NotAllowedException(Locale::getLocale('user is not allowed'));
        }
    }

    /**
     * @param int $errorCode
     * @param string $message
     */
    protected function badRequestWithError(int $errorCode, string $message):void
    {
        $this->httpResponse->response(
            new JSONResponse(
                ['error' => $errorCode,'message' => $message],
                Response::HTTP_RESPONSE_BAD_REQUEST
            )
        );
    }

    /**
     * @param int $errorCode
     * @param string $message
     */
    protected function unauthorizedWithError(int $errorCode, string $message):void
    {
        $this->httpResponse->response(
            new PrintErrorResponse(
                $errorCode,
                $message,
                Response::HTTP_RESPONSE_UNAUTHORIZED
            )
        );
    }

    protected function unauthorizedBasicAuth()
    {
        $this->httpResponse->response(
            new Response(
                '',
                Response::CONTENT_TYPE_HTML,
                Response::HTTP_RESPONSE_UNAUTHORIZED,
                Response::BASIC_AUTH_ACCESS_DENIED
            )
        );
    }

    /**
     * @param string $action
     * @return boolean
     */
    protected function isUserAllowed(string $action): bool
    {
        $repo = $this->getEntityManager()->getRepository(User::class);
        $session = $this->getSession();
        $user = $repo->findOneById($session->get('user')['id']);
        return $this->getUserService()->userCan($user, $action);
    }

    /**
     * @return string
     */
    protected function generateCSRFToken(): string
    {
        $session = $this->getSession();
        $token  = $this->getCsrfFactory()->createToken();
        $session->set(CSRFToken::ID, $token->getTokenValue());

        return $token->getTokenValue();
    }

    /**
     * @param $requestToken
     * @throws InvalidCSRFException
     */
    protected function validateCSRFToken($requestToken)
    {
        $session = $this->getSession();
        $sessionToken = $session->get(CSRFToken::ID);
        if (false === hash_equals($requestToken, $sessionToken)) {
            throw new InvalidCSRFException(
                "csrf tokens do not match",
                ['session token' => $sessionToken, 'form token' => $requestToken]
            );
        }
        $session->unsetByKey('csrf');
    }

    /**
     * @param $name
     * @param $arguments
     * @return object
     * @throws ReflectionException
     * using magic methods to retrieve from DIContainer
     */
    public function __call($name, $arguments): object
    {
        $id = lcfirst(str_replace('get', '', $name));
        return $this->diContainer->get($id);
    }
}

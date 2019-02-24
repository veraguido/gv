<?php namespace Gvera\Controllers;

use Gvera\Exceptions\InvalidCSRFException;
use Gvera\Exceptions\InvalidHttpMethodException;
use Gvera\Exceptions\InvalidMethodException;
use Gvera\Exceptions\InvalidViewException;
use Gvera\Helpers\dependencyInjection\DIContainer;
use Gvera\Helpers\locale\Locale;
use Gvera\Helpers\security\CSRFToken;

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

    private $method = null;
    private $name;
    private $twig;
    protected $viewParams = array();
    protected $httpResponse;
    protected $httpRequest;
    protected $diContainer;
    protected $protectedController = false;

    const DEFAULT_CONTROLLER = "Index";
    const DEFAULT_METHOD = 'index';
    private $twigService;

    /**
     * GvController constructor.
     * @param DIContainer $diContainer
     * @param $controllerName
     * @param string $method
     * @throws \Exception
     */
    public function __construct(DIContainer $diContainer, $controllerName, $method)
    {
        $this->diContainer = $diContainer;
        $this->method = $method;
        $this->name = $controllerName;
        $this->httpRequest = $this->diContainer->get('httpRequest');
        $this->httpResponse =$this->diContainer->get('httpResponse');
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
     * @throws \Exception
     */
    public function init($allowedHttpMethods = [])
    {
        $this->preInit($allowedHttpMethods);

        $tmpM = $this->method;
        $this->$tmpM();

        $this->postInit();
    }

    /**
     * @param $allowedHttpMethods
     * @throws InvalidHttpMethodException
     * @throws \ReflectionException
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
     * @throws \Exception
     */
    protected function postInit()
    {
        if ($this->twigService->needsTwig($this->name, $this->method)) {
            $this->httpResponse->response(
                $this->twigService->render($this->name, $this->method, $this->viewParams)
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

    public function checkIfPassIsGranted()
    {
        if (true === $this->protectedController && false === $this->checkAuthorization()) {
            if ($this->httpRequest->isAjax()) {
                $this->httpResponse->unauthorized();
                exit();
            }

            $this->httpResponse->redirect("/");
            exit();
        }
    }

    /**
     * @param int $errorCode
     * @param string $message
     */
    protected function badRequestWithError(int $errorCode, string $message)
    {
        $this->httpResponse->asJson();
        $this->httpResponse->badRequest();
        $this->httpResponse->response([
            'error' => $errorCode,
            'message' => $message
        ]);
    }

    /**
     * @param int $errorCode
     * @param string $message
     */
    protected function unauthorizedWithError(int $errorCode, string $message)
    {
        $this->httpResponse->asJson();
        $this->httpResponse->unauthorized();
        $this->httpResponse->printError($errorCode, $message);
    }

    /**
     * @return bool
     */
    protected function checkAuthorization(): bool
    {
        return $this->getUserService()->isUserLoggedIn();
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
    }

    /**
     * @param $name
     * @param $arguments
     * @return object
     * @throws \ReflectionException
     * using magic methods to retrieve from DIContainer
     */
    public function __call($name, $arguments)
    {
        $id = lcfirst(str_replace('get', '', $name));
        return $this->diContainer->get($id);
    }
}

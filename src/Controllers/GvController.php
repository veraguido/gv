<?php namespace Gvera\Controllers;

use Gvera\Exceptions\InvalidCSRFException;
use Gvera\Exceptions\InvalidHttpMethodException;
use Gvera\Exceptions\InvalidMethodException;
use Gvera\Exceptions\InvalidViewException;
use Gvera\Helpers\dependencyInjection\DIContainer;
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

    const VIEWS_PREFIX = __DIR__ . '/../Views/';
    const DEFAULT_CONTROLLER = "Index";
    const DEFAULT_METHOD = 'index';
    const HTTP_CODE_REPONSE_CONTROLLER_NAME = 'HttpCodeResponse';

    /**
     * GvController constructor.
     * @param DIContainer $diContainer
     * @param $controllerName
     * @param string $method
     * @throws \Exception
     */
    public function __construct(DIContainer $diContainer, $controllerName, $serverRequest, $serverResponse, $method = 'index')
    {
        $this->diContainer = $diContainer;
        $this->method = $method;
        $this->name = $controllerName;
        $this->httpRequest = $this->diContainer->get('httpRequest');
        $this->httpRequest->setServerRequest($serverRequest);
        $this->httpResponse = $this->diContainer->get('httpResponse');
        $this->httpResponse->setServerResponse($serverResponse);

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
        $this->checkAllowedHttpMethods($allowedHttpMethods);

        if ($this->needsTwig()) {
            $loader = new \Twig_Loader_Filesystem(self::VIEWS_PREFIX);
            $this->twig = new \Twig_Environment($loader);
        }
    }

    /**
     * @throws \Exception
     */
    protected function postInit()
    {
        if ($this->needsTwig()) {
            $this->httpResponse->response(
                $this->twig->render(
                    '/' . $this->name . '/' . $this->method . '.twig.html',
                    $this->viewParams
                )
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
     * @return bool
     */
    protected function needsTwig()
    {
        return file_exists(self::VIEWS_PREFIX . $this->name . '/' . $this->method . '.twig.html');
    }

    /**
     * @param int $errorCode
     * @param string $message
     */
    protected function badRequestWithError(int $errorCode, string $message)
    {
        $this->httpResponse->asJson();
        $this->httpResponse->badRequest();
        $this->httpResponse->printError($errorCode, $message);
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
     * @return bool
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

    private function checkAllowedHttpMethods($allowedMethods) {
        $annotationUtil = $this->diContainer->get('annotationUtil');
        $isHttpMethodValid = $annotationUtil->validateMethods(
            $allowedMethods,
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
        if ($this->diContainer->has($id)) {
            return $this->diContainer->get($id);
        }
    }
}

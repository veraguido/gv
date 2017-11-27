<?php namespace Gvera\Controllers;

use Doctrine\ORM\EntityManager;
use Gvera\Helpers\http\HttpRequest;
use Gvera\Helpers\http\HttpResponse;

/**
 * Class GvController
 * @package Gvera\Controllers
 * Base controller to be used as a parent of all controllers, manages http objects,
 * the entityManager and the responsibility of loading twig or not.
 */
abstract class GvController
{

    private $method = null;
    private $name;
    private $twig;
    protected $viewParams = array();
    protected $httpResponse;
    protected $httpRequest;
    protected $entityManager;

    const VIEWS_PREFIX = __DIR__ . '/../Views/';
    const DEFAULT_CONTROLLER = "Index";
    const DEFAULT_METHOD = 'index';
    public const HTTP_CODE_REPONSE_CONTROLLER_NAME = 'HttpCodeResponse';

    /**
     * GvController constructor.
     * @param $controllerName
     * @param string $method
     * @param $entityManager
     * @throws \Exception
     */
    public function __construct($controllerName, $method = 'index', $entityManager)
    {
        $this->method = $method;
        $this->name = $controllerName;
        $this->httpResponse = HttpResponse::getInstance();
        $this->httpRequest = HttpRequest::getInstance();
        $this->entityManager = $entityManager;
        if(!method_exists($this, $method)) {
            throw new \Exception('the method ' . $method . ' was not found on:' . __FILE__ . ' controller');
        }
    }

    protected function preInit() {
        if ($this->needsTwig()) {
            $loader = new \Twig_Loader_Filesystem(self::VIEWS_PREFIX);
            $this->twig = new \Twig_Environment($loader);
        }
    }

    /**
     * Initialization method.
     */
    public function init() {
        $this->preInit();

        $tmpM = $this->method;
        $this->$tmpM();

        $this->postInit();
    }


    protected function postInit() {
        if($this->needsTwig()) {
            echo $this->twig->render('/'.$this->name . '/' . $this->method . '.twig.html', $this->viewParams);
            return;
        }

        if(count($this->viewParams) > 0)
            throw new \Exception('view params was set, but view could not be found for ' . $this->method . ' method, Controller: ' . $this->name);
    }


    protected function needsTwig() {
        return file_exists(self::VIEWS_PREFIX . $this->name . '/' . $this->method . '.twig.html');
    }

}
<?php namespace Gvera\Controllers;

use Gvera\Helpers\http\HttpRequest;
use Gvera\Helpers\http\HttpResponse;

abstract class GController
{

    private $method = null;
    private $name;
    private $twig;
    protected $viewParams = array();
    protected $httpResponse;
    protected $httpRequest;

    const VIEWS_PREFIX = __DIR__ . '/../Views/';
    const DEFAULT_METHOD = 'index';
    const HTTP_RESPONSE_CODE_CONTROLLER_NAME = 'HttpCodeResponse';

    public function __construct($controllerName, $method = 'index')
    {
        $this->method = $method;
        $this->name = $controllerName;
        $this->httpResponse = HttpResponse::getInstance();
        $this->httpRequest = new HttpRequest();
        if(!method_exists($this, $method)) {
            throw new \Exception('the method ' . $method . ' was not found on:' . __FILE__ . ' controller');
        }
    }

    protected function preInit() {
        if ($this->needsTwig()) {
            $loader = new \Twig_Loader_Filesystem(self::VIEWS_PREFIX);
            $this->twig = new \Twig_Environment($loader);
            //$this->httpResponse->asJson();
        }
    }


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

        if($this->viewParams !== null)
            throw new \Exception('view could not be found for ' . $this->method . ' method, Controller: ' . $this->name);
    }


    protected function needsTwig() {
        return file_exists(self::VIEWS_PREFIX . $this->name . '/' . $this->method . '.twig.html');
    }

}
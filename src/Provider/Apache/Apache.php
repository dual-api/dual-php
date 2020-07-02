<?php
namespace Dual\Provider;

class Apache extends \Dual\Provider\Template {

    private $controller_namespace   = null;
    private $cors                   = null;
    private $base_url               = null;
    private $debug_mode             = false;

    public function __construct(array $configs) {
        $this->controller_namespace = $configs['controller_namespace'];
        $this->cors                 = $configs['cors'];
        $this->base_url             = $configs['base_url'];
        $this->debug_mode           = $configs['debug_mode'];
        $this->environment          = $configs['environment'];
        $this->controller           = $this->__getControllerClass();
        $this->method               = $this->__getControllerMethod();
    }

    public function controllerExists() {
        return class_exists($this->controller);
    }

    public function methodExists() {
        return method_exists($this->controller, $this->method);
    }

    public function logDebug($error_message) {
        if ($this->$debug_mode) {
            ob_start();
            error_log($error_message, 0);
            debug_print_backtrace();
            error_log(ob_get_clean());
        }
    }

    public function getController() {
        return new $this->controller();
    }

    public function getMethod() {
        return $this->method;
    }

    public function getRequest() {
        $Request = new \Dual\Provider\Apache\Request($this->environment);
        return $Request;
    }

    public function getResponse() {
        $Response = new \Dual\Provider\Apache\Response();
        if ($this->cors) $Response->setCors($this->base_url);
        return $Response;
    }

    private function __getControllerClass() {
        $query  = explode('?', $_SERVER['REQUEST_URI']);
        $dot    = explode('.', $query[0]);
        $slash  = explode('/', end($dot));
        array_shift($slash);
        return $this->controller_namespace.implode('\\', $slash);
    }

    private function __getControllerMethod() {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
}

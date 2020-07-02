<?php
namespace Dual\Provider;

class Apache extends \Dual\Provider\Template {

    private $controller_namespace   = null;
    private $cors                   = null;
    private $base_url               = null;
    private static $endpoint_class  = null;
    private static $endpoint_method = null;
    private static $debug_mode      = false;

    public function __construct(array $configs) {
        $this->controller_namespace = $configs['controller_namespace'];
        $this->cors                 = $configs['cors'];
        $this->base_url             = $configs['base_url'];
        $this->controller           = $this->__getControllerClass();
        $this->method               = $this->__getControllerMethod();
        self::$endpoint_class       = $this->controller;
        self::$endpoint_method      = $this->method;
        self::$debug_mode           = $configs['debug_mode'];
    }

    public static function classExists() {
        return class_exists(self::$endpoint_class);
    }

    public static function methodExists() {
        return method_exists(self::$endpoint_class, self::$endpoint_method);
    }

    public static function logDebug($error_message) {
        if (self::$debug_mode) error_log($error_message, 0);
    }

    public function getRequestClass() {
        $Request = new \Dual\Provider\Apache\Request();
        return $Request;
    }

    public function getResponseClass() {
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

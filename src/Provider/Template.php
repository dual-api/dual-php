<?php
namespace Dual\Provider;

abstract class Template {

    protected $controller   = null;
    protected $method     = null;

    public function beforeRoute(\Dual\Provider\Apache\Request $Request, \Dual\Provider\Apache\Response &$Response) {
        $pass = (new $this->controller())->before($Request, $Response);
        $this->__evalPass($pass, 'before');
    }

    public function invoke(\Dual\Provider\Apache\Request $Request, \Dual\Provider\Apache\Response &$Response) {
        (new $this->controller())->$this->method($Request, $Response);
    }

    public function afterRoute(\Dual\Provider\Apache\Request $Request, \Dual\Provider\Apache\Response &$Response) {
        (new $this->controller())->after($Request, $Response);
        $this->__evalPass($pass, 'after');
    }

    private function __evalPass(bool $pass, string $route) {
        if (!$pass) throw new Exception("error triggered during: {$route}");
    }

}

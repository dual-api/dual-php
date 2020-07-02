<?php
namespace Dual;

final class Router {

    private $Controller = null;
    private $Request    = null;
    private $Response   = null;

    public function __construct($controller) {
        $this->Controller   = $controller;
        $this->Request      = $this->Controller->getRequestClass();
        $this->Response	    = $this->Controller->getResponseClass();
    }

    public function route() {
        if (!$this->Controller::classExists()) {
            $this->Response->setCode(404);
            $this->Response->setBody(['errors' => ['endpoint not found']]);
        } elseif(!$this->Controller::methodExists()) {
            $this->Response->setCode(405);
            $this->Response->setBody(['errors' => ['method not allowed']]);
        } else {
            $this->__processRequest();
        }
        return $this->Response;
    }

    private function __processRequest() {
        try {
            $this->Controller->beforeRoute($this->Request, $this->Response);
            $this->Controller->invoke($this->Request, $this->Response);
            $this->Controller->afterRoute($this->Request, $this->Response);
        } catch(\Exception $exception) {
            $this->Response->setCode(500, false);
            $this->Controller::logDebug($exception->getMessage());
        }
    }
}

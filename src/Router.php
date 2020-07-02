<?php
namespace Dual;

final class Router {

    private $Provider   = null;
    private $Request    = null;
    private $Response   = null;

    public function __construct(\Dual\Provider $provider) {
        $this->Provider = $provider;
        $this->Request  = $this->Provider->getRequest();
        $this->Response = $this->Provider->getResponse();
    }

    public function route() {
        if (!$this->Provider->controllerExists()) {
            $this->Response->setCode(404);
            $this->Response->setBody(['errors' => ['endpoint not found']]);
        } elseif(!$this->Provider->methodExists()) {
            $this->Response->setCode(405);
            $this->Response->setBody(['errors' => ['method not allowed']]);
        } else {
            $Controller = $this->Provider->getController();
            $method     = $this->Provider->getMethod();
            $this->__processRequest($Controller, $method);
        }
        return $this->Response;
    }

    private function __processRequest($Controller, $method) {
        try {
            $Controller->beforeRoute($this->Request, $this->Response);
            $Controller->$method($this->Request, $this->Response);
            $Controller->afterRoute($this->Request, $this->Response);
        } catch(\Exception $exception) {
            $this->Response->setCode(500, false);
            $this->Provider->logDebug($exception->getMessage());
        }
    }
}

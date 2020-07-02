<?php
namespace Dual;

class Provider {

    private $providers = [
        'apache'    => '\Dual\Provider\Apache',
        'aws'       => '\Dual\Provider\aws',
        'nginx'     => '\Dual\Provider\Nginx'
    ];

    public function __construct(array $configs, $event = []) {
        $this->configs  = $configs;
        $this->event    = $event;
    }

    public function getController() {
        $endpoint_class = $this->$providers[$this->configs['provider']];
        return new $endpoint_class($this->configs, $this->event);
    }

}

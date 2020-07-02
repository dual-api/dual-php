<?php
namespace Dual;

class ProviderFactory {

    private $providers = [
        'apache'    => '\Dual\Provider\Apache',
        'aws'       => '\Dual\Provider\aws',
        'nginx'     => '\Dual\Provider\Nginx'
    ];

    public function __construct(array $configs, $event = []) {
        $this->configs  = $configs;
        $this->event    = $event;
    }

    public function getProvider() {
        $provider = $this->$providers[$this->configs['provider']];
        return new $provider($this->configs, $this->event);
    }

}

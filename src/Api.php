<?php
namespace Dual;

final class Api {

    private $configs;

    public function __construct(array $configs = []) {
        $this->configs = $configs;
        $this->__checkConfigs();
    }

    public function run(array $event = []) {
        $Factory    = new \Dual\ProviderFactory($this->configs, $event);
        $Provider   = $Factory->getProvider();
        $Router     = new \Dual\Router($Provider);
        $Response   = $Router->route();
        $Response->respond();
    }

    private function __checkConfigs() {
        $this->__checkProvider();
        $this->__checkPath('base_url');
        $this->__checkPath('controller_namespace');
    }

    private function __checkProvider(string $provider) {
        if (!in_array($this->configs['provider'], ['apache', 'nginx', 'aws'])) {
            throw new Exception("Provide valid provider: apache|aws|nginx");
        }
    }

    private function __checkPath(string $path) {
        if (!$this->configs[$path]) throw new Exception("Provide valid path for {$path}");
    }
}

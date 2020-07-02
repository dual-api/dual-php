<?php
namespace Dual\Provider\Apache;

class Request {

    private $environment = null;

    public function __construct($environment) {
        $this->environment = $environment;
    }

    public function environment() {
        return $this->environment;
    }

    public function headers() {
        return getallheaders();
    }

    public function jsonBody() {
        $json = json_decode(file_get_contents("php://input"), true);
        if (empty($json)) {
            throw new \Exception('endpoint expecting JSON body; body is either malformed or empty');
        }
        return $json;
    }

    public function files() {
        return $_FILES;
    }

    public function postBody() {
        return $_POST;
    }

    public function queryStringParameters() {
        return $_GET;
    }
}

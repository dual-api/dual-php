<?php
namespace Dual\Provider\Apache;

class Request {

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

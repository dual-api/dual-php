<?php
namespace Dual\Provider\Apache;

class Response {

    private $body       = null;
    private $code       = 0;
    private $is_json    = false;
    private $headers    = [];

    public function setCors(string $base_url) {
        $this->addHeader('Access-Control-Allow-Origin', $base_url);
    }

    public function addHeader(string $name, string $value) {
        $this->headers[$name] = $value;
    }

    public function getHeaders() {
        $this->addHeader('Content-Length', ob_get_length());
        if ($this->is_json) {
            $this->addHeader('Content-Type', 'application/json');
        }
        return $this->headers;
    }

    public function setCode(int $code, $overwrite = true) {
        if ($overwrite){
            $this->code = (int) $code;
        } elseif (!$overwrite && $this->code === 0){
            $this->code = (int) $code;
        }
    }

    public function getCode(){
        if ($this->code === 0) {
            return 200;
        }
        return $this->code;
    }

    public function setJson($json) {
        $this->is_json = true;
        $this->body = $json;
    }

    public function setBody($body) {
        $this->body = $body;
    }

    public function getBody() {
        return ($this->is_json) ? json_encode($this->body) : $this->body;
    }

    public function respond() {
        http_response_code($this->getCode());
        echo $this->getBody();
        foreach ($this->getHeaders() as $key => $value) {
        	header("$key: $value");
        }
    }
}

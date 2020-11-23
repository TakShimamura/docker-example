<?php

namespace App\Support\Facades;

use GuzzleHttp\Psr7\Response as GuzzleResponse;

class Response {



    public $status;
    public $reason;
    public $version;
    public $headers;
    public $stream;
    public $body;

    public function __construct(GuzzleResponse $response){
        $this->status = $response->getStatusCode();
        $this->reason = $response->getReasonPhrase();
        $this->version = $response->getProtocolVersion();
        $this->headers = $response->getHeaders();
        $this->stream = $response->getBody();
        $this->body = $this->stream->getContents();
    }

    public function json(){
        return json_decode($this->body,true);
    }

    public function header(string $key,$default=null){
        return FindOrDefault($key,$this->headers,$default);
    }

    public function successfull(){
        return (
            $this->status >= 200
            && $this->status < 300
        );
    }

    public function redirected(){
        return (
            $this->status >= 300
            && $this->status < 400
        );
    }

    public function clientError(){
        return (
            $this->status >= 400
            && $this->status < 500
        );
    }

    public function serverError(){
        return (
            $this->status >= 500
            && $this->status < 600
        );
    }




}
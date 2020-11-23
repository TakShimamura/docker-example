<?php

namespace App\Support\Facades;


use GuzzleHttp\Client;
use App\Support\Facades\Response;

class HttpClient {


    public static function post(string $url,array $params){
        $client = new Client(['base_uri' => $url, 'http_errors' => false]);       
        return new Response($client->request('POST', $url, $params));       
    }

    public static function get(string $url, array $params){
        $client = new Client(['base_uri' => $url, 'http_errors' => false]);       
        return new Response($client->request('GET', $url, $params));    
    }

    // public function buildURL(string $url, array $params){

    // }

}
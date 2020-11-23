<?php

namespace App\Support\Traits\Uses;

use Stripe\StripeClient;

trait Stripe {

    protected $client;

    public static function key($type='secret'){
        $access = (isProduction()) ? 'live' : 'test';
        return config('services.stripe.'.$access.'.keys.'.$type);
    }

    public function __construct(){
        $this->client = new StripeClient(self::key());
    }


    public function products(){
        return $this->client->products;
    }

    public function prices(){
        return $this->client->prices;
    }

    public function coupons(){
        return $this->client->coupons;
    }

    public function customers(){
        return $this->client->customers;
    }

    
    public function paymentMethods(){
        return $this->client->paymentMethods;
    }
    
    public function subscriptions(){
        return $this->client->subscriptions;
    }

    public function paymentIntents(){
        return $this->client->paymentIntents;
    }

}
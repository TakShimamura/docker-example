<?php

namespace App\Contracts;



interface CustomerManagementService
{

    public function products();

    public function prices();

    public function coupons();

    public function customers();
    
    public function paymentMethods();
    
    public function subscriptions();

    public function paymentIntents();

}

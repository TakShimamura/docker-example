<?php 

namespace App\Support\Services;

use App\Contracts\CustomerManagementService;
use App\Support\Traits\Uses\Stripe;

class CustomerManager implements CustomerManagementService {

    use Stripe;

}
<?php

namespace App\V1\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestsController extends Controller
{
    //

    protected function respects($param){
        if(is_string($param) && strtolower($param) == 'abort' || strtolower($param) == 'error'){
            return false;
        }
        return true;
    }

    public function test(Request $request, $param=null){
        if(empty($param)){
            return response()->json([
                'version'       => version(),
                'controller'    => controller('Tests'),
                'user'          => Auth::user(),
                'users-random'  => Auth::user()->random(12),
                'encrypted'     => rsa()->encrypt('Test'),
                'jwt'           => jwt()->encode(['test' => 'jwt']),
                'jwt-encrypted' => jwt()->encrypt(['test' => 'jwt-encrypted']),
            ]);
        }

        if($this->respects($param)){
            return response()->json($param);
        }

        AbortRequest(['Recieved a disrespectful param... ','Testing abort request.'],400);
    }

}

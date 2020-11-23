<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Contracts\User;

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
            return response()->json(payload([
                'version'       => version(),
                'controller'    => controller('Tests'),
                'user'          => Auth::user(),
                // 'users-random'  => Auth::user()->random(12),
                'request'       => [
                    'fwd'   => $request->header(Request::HEADER_FORWARDED),
                    'fwd-for' => $request->header(Request::HEADER_X_FORWARDED_FOR),
                    'fwd-aws-elb' => $request->header(Request::HEADER_X_FORWARDED_AWS_ELB),
                    'fwd-host'  => $request->header(Request::HEADER_X_FORWARDED_HOST),
                ],
                'encrypted'     => rsa()->encrypt('Test'),
                'jwt'           => jwt()->encode(['test' => 'jwt']),
                'jwt-encrypted' => jwt()->encrypt(['test' => 'jwt-encrypted']),
            ]));
        }
        
        if($param == 'rules'){
            $action = endpoint()->rule;
            return response()->json([
                'action' => $action,
                'rules' => endpoint()->rules()
            ]);
        }

        if($param == 'request'){
            return response()->json(
                payload([
                    'request' => new \ReflectionClass(app()->request),
                    'Route' => \Route::currentRouteAction(),
                ])
            );
        }

        if($param == 'user'){
            return response()->json(
                payload([
                    'test-token' => jwt()->encrypt([
                        'id' => 'sample',
                        'fingerprint' => 'sample',
                        'user' => [
                            'id' => 'sample_1234',
                            'clearances' => ['administrator'=>strtotime(now()->addDays(1))]
                        ]
                    ])
                ]),
                200
            );
        }

        if($this->respects($param)){
            return response()->json($param);
        }

        AbortRequest(['Recieved a disrespectful param... ','Testing abort request.'],400);
    }

}

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


    public function test(Request $request, $param=null) {
        if(empty($param)){
            return response()->json(payload([
                'version'       => version(),
                'subject'       => subject(),
                'controller'    => controller('tests'),
                'user'          => Auth::user(),
            ]));
        }

        if ($param == 'json') {
            $filename = $request->input('file');
            return AbortOnFail(
                Attempt('GiveFile')->feed(['responses', $filename])->try(),
                ['File not found.'],
                404
            );
        }

        if($param == 'encrypt'){
            $value = $request->input('value');
            $key = $request->input('key');
            // return $key;
            return respond(
                rsa($key)->encrypt($value),
                200
            );
        }

        if($param == 'decrypt'){
            $value = $request->input('value');
            return $value;
            $key = $request->input('key');
            return respond(
                rsa($key)->decrypt($value),
                200
            );

        }

        if($param == 'rules'){
            $action = endpoint()->rule;
            return respond([
                'action' => $action,
                'rules' => endpoint()->rules()
            ]);
        }

        if($param == 'request'){
            return response()->json(
                payload([
                    'request' => new \ReflectionClass(app()->request),
                    'Route' => \Route::currentRouteAction(),
                    'fwd'   => $request->header(Request::HEADER_FORWARDED),
                    'fwd-for' => $request->header(Request::HEADER_X_FORWARDED_FOR),
                    'fwd-aws-elb' => $request->header(Request::HEADER_X_FORWARDED_AWS_ELB),
                    'fwd-host'  => $request->header(Request::HEADER_X_FORWARDED_HOST),
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
                            'id' => '1',
                            'clearances' => ['standard'=>strtotime(now()->addDays(1))]
                        ]
                    ])
                ]),
                200
            );
        }

        if($param == 'admin'){
            return response()->json(
                payload([
                    'test-token' => jwt()->encrypt([
                        'id' => 'sample',
                        'fingerprint' => 'sample',
                        'user' => [
                            'id' => '1',
                            'clearances' => ['administrator'=>strtotime(now()->addDays(1))]
                        ]
                    ])
                ]),
                200
            );
        }

        if($param == 'support'){
            return response()->json(
                payload([
                    'test-token' => jwt()->encrypt([
                        'id' => 'sample',
                        'fingerprint' => 'sample',
                        'user' => [
                            'id' => 'sample_1234',
                            'clearances' => ['support'=>strtotime(now()->addDays(1))]
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

<?php

namespace App\Http\Controllers;

use Auth;
use App\Http\Requests\Request;
use App\Http\Requests\TestRequest;
use Illuminate\Http\Request as LarryRequest;

class FishesController extends Controller
{
    //

    protected $subject = 'fish';

    public $rules = [
        'create' => [
            'validations' => [
                'name' => ['required','string','min:4'],
                'age' => ['required','string','min:4'],
                'breed' => ['required','string','min:4']
            ],
            'normalizations' => [
                'name' => ['trim'],
                'breed' => ['strtoupper'],
            ],
            'decrypt' => [
                'key' => '1k',
                'fields' => [
                    'breed'
                ]
            ]
        ],
    ];


    public function create(Request $request){
        $user = $request->user();
        $payload = $request
            ->with($this->rules['create'])
            ->validate();

        return $payload;

        $fish = $user->fishes()->create($payload);

        return $this->show($request,$fish->id);

    }

    public function show(Request $request, $id=1){
        $user = $request->user();

        $fish = FindOrAbort(
            $user->fish($id),
            ['No fish found.'],
            404
        );

        return response()->json(
            payload($fish),
            200
        );
    }

    public function index(Request $request){
        $user = $request->user();

        return response()->json(
            payload($user->fishes()->paginate()),
            200
        );
    }
}

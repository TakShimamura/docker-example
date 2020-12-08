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

    public function create(Request $request){
        $user = $request->user();

        $payload = $request->validate();

        $payload['type_id'] = \App\Models\FishType::find(1)->id;

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

        return respond(
            $fish,
            200
        );
    }

    public function index(Request $request){
        $user = $request->user();

        $payload = $request->validate();

        return respond(
            $user->fishes()
            ->with('type')
            ->orderBy($payload['order_by'])
            ->paginate($payload['per_page']),
            200
        );
    }
}

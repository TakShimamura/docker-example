<?php

namespace App\Http\Controllers;


use App\Http\Requests\Request;

class CrudController extends Controller
{

    public function create(Request $request){
        $record = $this->store($request);

        return $this->show($record);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // TODO:: admins can index all users.

        $payload = $request->validate();

        $orderBy = FindOrDefault('order_by',$payload,'created_at');
        $perPage = FindOrDefault('per_page',$payload,15);

        return respond(
            // \App\Models\User::paginate(),
            contract(subject())->orderBy($orderBy)->paginate($perPage),
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //   
        $payload = $request->validate();
        
        $record = contract(subject())->create($payload);

        return $record;
    }

    /**
     * Display the specified resource.
     * 
     * @param \App\Http\Requests\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($record)
    {

        $data = $this->find($record);

        return respond(
            $data,
            200
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        // return $request->with()->rules();
        $payload = $request->validate();


        $record = $this->find($id);

        AbortIfFalse(
            $record->validate($payload),
            $record->errors(),
            400,
        );

        AbortIfFalse(
            $record->fill($payload)->update(),
            ['Problem saving record. Please try again.'],
            400
        );

        $record->refresh();
        
        return respond(
            $record,
            200
        );

    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param \App\Http\Requests\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //
        $record = $this->find($id);

        AbortIfFalse(
            $record->delete(),
            ['Problem occured while deleting this record. Please try again.'],
            400
        );

        return respond(
            [
                'status' => 'deleted',
                $record
            ],
            200
        );
    }

    /**
     * get the specified resource from storage.
     * 
     * @param \App\Http\Requests\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function retrieve(Request $request, $id)
    {
        //
        $user = $request->user();
        
        $record = FindOrAbort(
            $user->access($id),
            ['Cannot access that record.', 'Please try logging out and back in.'],
            401
        );

        return $this->show($record);
    }

}

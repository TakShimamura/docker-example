<?php

namespace App\Http\Requests;

use Auth;
use  Illuminate\Foundation\Http\FormRequest as LarryRequest;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Exceptions\HttpResponseException;

class Request extends LarryRequest
{

    /**
     * container for rules.
     */
    protected $with = [];
    /**
     * container for validation rules.
     */
    protected $validations = [];
    /**
     * container for modifications.
     */
    protected $modifications = [];
    /**
     * container for encrypted fields.
     */
    protected $encrypted = [];



    /**
     * Ensures a user is retrieved
     */
    public function user($guard = null){
        $user = Auth::user();
        if(empty($user)){
            AbortRequest(['Unauthorized: Session Expired, Please log in.'], 401);
        }
        return $user;
    }

    /**
     * Throw renderable response.
     */
    protected function failedValidation(ValidatorContract $validator){
        foreach($validator->errors()->all() as $key => $value){
            $response[] = $value;
        }

        // throw new HttpResponseException(response()->json($response,422));
        AbortRequest($response,422);
    }


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return $this->validations;
        // if(!empty($this->validations)){
        //     return $this->validations;
        // }

        // return $this->with()->validations;
    }

    protected function getRules($rules = null){
        if(!empty($rules)){ return $rules; }
        return endpoint()->rules();
    }

    /**
     * pass in rules and return instance
     * gives the ability to chain functions
     */
    public function with($rules = null){
        $this->with = $this->getRules($rules);
        return $this
            ->setValidationRules()
            ->setFieldsToNormalize()
            ->setEncryptedFields();
    }

    public function setValidationRules(){
        $this->validations = FindOrDefault('validations',$this->with,[]);
        return $this;
    }

    public function setFieldsToNormalize(){
        $this->modifications = FindOrDefault('normalizations',$this->with,[]);
        return $this;
    }

    public function setEncryptedFields(){
        $decrypt             = FindOrDefault('decrypt',$this->with,[]);
        if(empty($decrypt)){ return $this; }
        $this->encrypted     = FindOrDefault('fields',$decrypt,[]);
        $this->decryptionKey = FindOrDefault('key',$decrypt,'1k');
        return $this;
    }

    /**
     * validation override
     */
    public function validate(){
        $validator = Validator::make($this->data(),$this->rules());
        if($validator->fails()){
            return $this->failedValidation($validator);
        }
        $payload = $validator->validate($this->rules());
        if(endpoint()->action == 'index'){ 
            $payload['order_by'] = FindOrDefault('order_by',$payload,'created_at');
            $payload['per_page'] = FindOrDefault('per_page',$payload,15);
        }
        return $payload;
    }

    protected function data(){
        $data = $this->with()->all();
        foreach($this->encrypted as $key){
            if(array_key_exists($key,$data)){
                $data[$key] =  rsa($this->decryptionKey)->decrypt($data[$key]);
            }
        }
        normalize($data,$this->modifications);
        return $data;
    }
}

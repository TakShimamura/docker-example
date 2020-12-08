<?php 
if (!function_exists('GiveFile')) {
    function GiveFile($type, $file)
    {
        $path = resource_path("$type/$file");
        $file = \File::get($path);
        $type = \File::mimeType($path);
        $response = \Response::make($file, 200);
        $response->header('Content-Type', $type);
        return $response;
    }
}

if(!function_exists('subject')){
    function subject(){
        return config('subject');
    }
}

if(!function_exists('endpoint')){
    function endpoint($type='api'){
        return new \App\Support\Facades\Endpoint(app()->request,$type);
    }
}


if(!function_exists('respond')){
    function respond($data,int $status = 200){
        return response()->json(
            payload($data), 
            $status
        );
    }
}

if(!function_exists('payload')){
    function payload($object){
        $response = [];
        if($object instanceof \Illuminate\Pagination\LengthAwarePaginator){
            $data = $object->items();
            $response['total'] = $object->count();
            $response['page'] = $object->currentPage();
            $response['per_page'] = $object->perPage();
        } else { $data = $object; }
        $response['data'] = $data;
        $response['type'] = gettype($data);
        $response['subject'] = config('subject');
        return $response;
    }
}

if(!function_exists('FindOrAbort')){
    function FindOrAbort($object,array $errors=null,$httpCode=404){
        if(!found($object)){ AbortRequest($errors, $httpCode); }
        return $object;
    }
}

if(!function_exists('AbortOnFail')){
    function AbortOnFail($attempt){
        if($attempt instanceof \Exception){
            AbortRequest([$attempt->getMessage()]);
        }
        return $attempt;
    }
}

if(!function_exists('AbortIfTrue')){
    function AbortIfTrue($boolean,array $errors=null,$httpCode=404){
        if($boolean){ AbortRequest($errors, $httpCode); }
        return $boolean;
    }
}
if(!function_exists('AbortIfFalse')){
    function AbortIfFalse($boolean,array $errors=null,$httpCode=404){
        if(!$boolean){ AbortRequest($errors, $httpCode); }
        return $boolean;
    }
}

if(!function_exists('AbortOnFind')){
    function AbortOnFind($object,array $errors=null,$httpCode=409){
        if(found($object)){ AbortRequest($errors, $httpCode); }
    }
}

if(!function_exists('AbortRequest')){
    function AbortRequest(array $errors=null,$httpCode=400){
        throw contract('RenderableException',['errors'=>$errors,'httpCode'=>$httpCode]);
    }
}

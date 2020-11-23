<?php

namespace App\Contracts;

interface Attempt {

    public function __construct($function, $object=null, array $payload=[]);

    /**
     * add named parameters as payload to be used in the action 
     */
    public function with(array $payload);


    /**
     * add ordered parameters as the payload to be used in the action. 
     */
    public function feed(array $payload);

    /**
     * Specify an object that this function belongs to.
     */
    public function on($object);

    /**
     * builds are performs the currently defined action.
     */
    public function action();

    /**
     * This should try to perform the action
     * If the action is successful return the result,
     * If is it not return the caught exception.
     */
    public function try();

    /**
     * Same as try, except it will throw an exception 
     * throw the exception instead of handing it back.
     */
    public function AbortOnFail();

    /**
     * Define the type of exception 
     * to convert the caught exception to.
     * Perhaps something renderable? :P
     */
    public function throwOnFail($ExceptionClass,$httpCode=400,$errors=null);

}

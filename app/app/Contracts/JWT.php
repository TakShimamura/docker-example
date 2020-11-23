<?php

namespace App\Contracts;



interface JWT
{

    public function __construct($type=null,$encoding=null);

    public function key($type=null);

    public function mode($encoding=null);

    public function encrypt(array $payload,$size=null);

    public function decrypt($token,$size=null);

    public function encode(array $payload);

    public function decode($token);

}

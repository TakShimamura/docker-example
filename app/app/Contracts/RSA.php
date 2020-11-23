<?php

namespace App\Contracts;



interface RSA
{

    public function __construct($type=null,$encoding=null);

    public function key($type);

    public function mode($encoding);

    public function encrypt(string $string);

    public function decrypt($encrypted);

}

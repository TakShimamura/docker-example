<?php

namespace App\Contracts;



interface RenderableException
{

    public function __construct(array $errors, $httpCode);

    public function render($request);

}

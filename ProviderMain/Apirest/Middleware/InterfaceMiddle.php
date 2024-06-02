<?php

namespace ProviderMain\Apirest\Middleware;

use ProviderMain\Apirest\Request\Request;
use ProviderMain\Apirest\Response\Response;
use ProviderMain\Apirest\Route\RouteAs;

interface InterfaceMiddle
{
    public function Render($request,$other):void;
}
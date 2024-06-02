<?php

namespace ProviderMain\Apirest\Middleware;

use ProviderMain\Apirest\Request\Request;
use ProviderMain\Apirest\Response\Response;
use ProviderMain\Apirest\Route\RouteAs;
use ProviderMain\Error\ErrorValue;
use ProviderMain\SecuriteFile\Securite;
use ValueError;

class Middleware implements InterfaceMiddle
{
    use ConfigMiddle;

    public function __construct(protected $route,protected $middleware)
    {
        
    }
    public function Render($request, $other):void
    {
        //action nothing
    }
   
    
}

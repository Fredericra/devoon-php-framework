<?php

namespace File\Middleware\Admin;

use ProviderMain\Apirest\Middleware\Middleware;
use ProviderMain\Apirest\Route\RouteAs;
use ProviderMain\Autheticate\Admin as AutheticateAdmin;
use webApp\Model\User;

class Admin extends Middleware
{
   public function Render($request, $other):void
   {
     if(AutheticateAdmin::Is() && User::Authenticate()->username==="admin") 
     {

     } 
     else
     {
        RouteAs::To("main");
     }
   }
}
<?php

namespace File\Middleware\Auth;

use ProviderMain\Apirest\Middleware\Middleware;
use ProviderMain\Apirest\Route\RouteAs;
use ProviderMain\Autheticate\Admin;

class Auth extends Middleware
{
   public function Render($request, $other):void
   {
      if(Admin::Is())
      {
      }
      else
      {
          RouteAs::To("home");
      }
   }
}
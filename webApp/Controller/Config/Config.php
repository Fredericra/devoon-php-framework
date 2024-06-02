<?php

namespace webApp\Controller\Config;

use ProviderMain\Apirest\Controller\Controller;
use ProviderMain\Apirest\Route\RouteAs;

class Config extends Controller
{
    public function Config()
    {
         RouteAs::Page("Layout.Config");
    }
}
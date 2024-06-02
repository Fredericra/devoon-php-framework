<?php

namespace webApp\Controller;

use ProviderMain\Apirest\Controller\Controller;
use ProviderMain\Apirest\Request\Request;
use ProviderMain\Apirest\Route\RouteAs;

class IndexController extends Controller
{
    public function index()
    {
        RouteAs::Page('Main',['id'=>1]);
    }
}
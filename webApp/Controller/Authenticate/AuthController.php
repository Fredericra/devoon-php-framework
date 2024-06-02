<?php

namespace webApp\Controller\Authenticate;

use ProviderMain\Apirest\Controller\Controller;
use ProviderMain\Apirest\Request\Request;
use ProviderMain\Apirest\Route\RouteAs;
use ProviderMain\Autheticate\Admin;
use webApp\Model\User;
use ProviderMain\Storage\Storage;

class AuthController extends Controller
{

    public function main()
    {
        if(RouteAs::Method("GET"))
        {
            RouteAs::Page("Dashbord.Home");
        }
      
    }
    public function mains()
    {
       $move = Storage::Share("File")->move("iab0hlkv1713944174.jpg");
    }
    public function Destroy($id)
    {
        $admin = Admin::Auth();
        User::Destroy($admin);
        RouteAs::To("home");
    }
}
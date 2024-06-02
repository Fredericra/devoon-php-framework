<?php

namespace webApp\Controller\User;

use ProviderMain\Apirest\Controller\Controller;
use ProviderMain\Apirest\Request\Request;
use ProviderMain\Apirest\Route\RouteAs;
use ProviderMain\Autheticate\Admin;
use ProviderMain\Password\Password;
use ProviderMain\Validate\Validate;
use webApp\Model\User;
use ProviderMain\Storage\Storage;

class UserController extends Controller
{
   
    public function login(Request $request)
    {
        if(RouteAs::Method("GET"))
        {
            RouteAs::Page("Main");
        }
        else
        {
            Validate::The([
                "email"=>"require|email|exists:user",
                "username"=>"require|exists:user",
                "password"=>"require",
                "file"=>"file:2000|mix:1000|file:[img,jpg]"
            ]);
            if(User::Verify(["email"=>$request::All()->email,"username"=>$request::All("username"),"password"=>$request::All('password')]))
            {
                RouteAs::To('main');
            }
            elseif(!User::Where(["email"=>$request::All("email"),"username"=>$request::All("username")]))
            {
                Validate::Add(["username"=>"username not found"]);
            }
            else
            {
                Validate::Add(['password'=>"password errors"]);
            }
            RouteAs::Page("Main");

        }

    }
    public function Config()
    {
        
    }
    public function signin(Request $request)
    {
        if(RouteAs::Method("GET"))
        {
            RouteAs::Page("Main");
        }
        else{
            Validate::The([
                "email"=>"require|email|only:user",
                "username"=>"require|only:user|min:5|max:15",
                "password"=>"require|max:15|min:7",
                "confirm"=>"require|same:password",
            ]);
            $user = User::Create([
                "email"=>$request::All()->email,
                "username"=>$request::All()->username,
                "password"=>Password::Hash($request::All()->password)
            ]);
            User::Access($user);
            if(Admin::Is())
            {
                RouteAs::To("main");
            }
            else
            {
                RouteAs::Page("Main");
            }
        }
    }
    public function forget(Request $request)
    {
        RouteAs::Page("Layout.Forget");
    }
   
}

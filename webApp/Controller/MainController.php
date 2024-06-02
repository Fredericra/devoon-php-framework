<?php

namespace webApp\Controller;

use LDAP\Result;
use ProviderMain\Apirest\Controller\Controller;
use ProviderMain\Apirest\Request\Request;
use ProviderMain\Apirest\Route\RouteAs;
use ProviderMain\Guest\Guest;
use ProviderMain\SecuriteFile\Securite;
use webApp\Model\User;

class MainController extends Controller
{

    public function Home(Request $request)
    {
        $user = new User();
        $all = $user->user();
        $dash = Securite::require("Dash",[],"Dashbord",false);
        $object = json_decode(file_get_contents($dash));
        Guest::Visitor();
        RouteAs::Page("Main",["dash"=>$object]);
    }
    public function Admin()
    {
        RouteAs::Page("Admin.Admin");
    }
    public function guest()
    {
        RouteAs::Page("Main");
    }
    public function deleteguest(Request $request)
    {
        $emai = $request->All("email");
        Guest::Delete($emai);
        RouteAs::To('get.guest');
    }
    public function loginAdmin(Request $request)
    {
        $username = $request->All()->username;
        $allUser = User::Where(["username"=>$username]);
        RouteAs::Page("Main");
    }
  

    
}
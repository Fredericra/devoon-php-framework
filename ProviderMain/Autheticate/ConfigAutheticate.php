<?php

namespace ProviderMain\Autheticate;

use ProviderMain\Error\ErrorValue;

trait ConfigAutheticate
{
    protected static $auth; 
   public static function Is()
   {
        return isset($_SESSION['auth'])?$_SESSION['auth']:false;
   }
   //get
   public static function Auth(string $find="")
   {
    if(isset($_SESSION['auth']) && $_SESSION['auth'])
    {
        if(!empty($find))
        {
            return array_key_exists($find,$_SESSION['user'])?$_SESSION["user"][$find]:ErrorValue::ErrorValue("property $find is not found",[]);
        }
        else
        {
            return (object)$_SESSION['user'];
        }

    }
   }
}
<?php

namespace ProviderMain\Apirest\Request;

use ProviderMain\Error\ErrorValue;

trait RequestConfig
{
    private static $request = [];

    public static function getAll(array|string $data = []):void
    {
        if(isset($_GET))
        {
            array_push(self::$request,$data,$_GET);
        }
        elseif(isset($_POST))
        {
            array_push(self::$request,$data,$_POST);
        }
        elseif(isset($_FILES))
        {
            array_push(self::$request,$data,$_FILES);
        }
        
    }
    public static function All(string $find=""):object|string
    {
        $array = self::$request;
        $array  = call_user_func_array('array_merge',$array);
        if(!empty($find))
        {
            return array_key_exists($find,$array)?$array[$find]:ErrorValue::ErrorValue("property $find is not found",[]);
        }
        else
        {
            return (object)$array;
        }
    }
    public static function Array():array
    {
        $array = self::$request;
        $array  = call_user_func_array('array_merge',$array);
        return $array;
    }
    public function send():object
    {
        $request = Request::All();
        return (object)($request);
        
    }
}
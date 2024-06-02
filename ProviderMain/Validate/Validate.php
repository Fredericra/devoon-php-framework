<?php

namespace ProviderMain\Validate;

use ProviderMain\Error\Error;
use ProviderMain\Error\ErrorValue;

class Validate
{
    use ConfigValidate;
    public static $message = [];
    public static $AddMessage = [];
    public static function Add(array $validate=[])
    {
        $error = call_user_func_array("array_merge",self::$error);
        $map = array_filter($error,function($value,$index)use($validate){
            foreach ($validate as $key => $item) {
                return array_key_exists($key,$value);
            }
        },ARRAY_FILTER_USE_BOTH);
        if(count($map)===0)
        {
        self::$AddMessage = $validate;
        }
    }
    public static function Error(string $property="")
    {
        $error = self::$error;
        $message = [];
        $errors = count($error)!==0?call_user_func_array("array_merge",$error[0]):[];
        foreach ($errors as $key => $value) {
            $message[] = [$key=>$value['message']];
        }
        $message = call_user_func_array("array_merge",$message);
        self::$message = $message;
        if(count($message)!==0)
        {
            if(!empty($property))
            {
                if(array_key_exists($property,$message))
                {
                    return $message[$property];
                }
            }
            else
            {
                return $message;
            }
        }
      
        
    }
    
    public static function ErrorAS()
    {
        $error = self::$error;
        $message = [];
        $errors = count($error)!==0?call_user_func_array("array_merge",$error[0]):[];
        foreach ($errors as $key => $value) {
            $message[] = [$key=>$value['message']];
        }
        $message = call_user_func_array("array_merge",$message);
        self::$message = $message;
        if(isset($_POST) && $_SERVER['REQUEST_METHOD']==="POST")
        {
            $return  = isset($message)?$message:[];
            return $message;

        }
    }
    
}
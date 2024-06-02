<?php

namespace ProviderMain\Guest;

use webApp\Model\Visitor\Guest as Visitor;

class Guest
{
    public static function All():array
    {
        $guest = isset($_SESSION['guest'])?$_SESSION['guest']:[];
        $db = [];
        foreach ($guest as $key => $value) {
                $keys = $value[array_key_first($value)];
                if(isset($db[$keys]))
                {
                    $db['db'] = $value;
               }
               else
               {
                   $db[$keys] = $value;
                }
            }
            $return  = array_filter($db,function($value,$index){
                return $index!=='db';
            },ARRAY_FILTER_USE_BOTH);
            return call_user_func("array_merge",$return);
    }
    public static function Delete(array|string|int $data)
    {
        $guest = isset($_SESSION['guest'])?$_SESSION['guest']:[];
        $new = [];
        if(is_string($data))
        {
            foreach ($guest as $key => $value) {
                if(!in_array($data,$value))
                {
                    $new [] = $value;
                }
            }
        }
        $_SESSION['guest'] = $new;
        
    }
    public static function Visitor()
    {
        $user  =get_current_user();
        $cookie = $_SERVER['HTTP_COOKIE'];
        if(!Visitor::Where([
            "user"=>$user,
            "cookier"=>PHP_OS
        ]))
        {
            Visitor::Create([
                "user"=>$user,
                "cookier"=>PHP_OS
            ]);
        }
    }
}
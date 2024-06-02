<?php


namespace Provider\LoadingClass;

use ProviderMain\SecuriteFile\Securite;

class Save
{
    public static function Os(string $string)
    {
        $systeme = PHP_OS;
        if(preg_match("/(?i)(winnt|wint32)/",$systeme))
        {
           return str_replace("\\","/",$string);
        }
        elseif(preg_match("/(?i)(linux)/",$systeme))
        {
           return  str_replace("\\","/",$string);
        }

    }
    public static function ProviderFile(string $dirReplace,string $replace)
    {
        $systeme = PHP_OS;
        if(preg_match("/(?i)(winnt|wint32)/",$systeme))
        {
           return str_replace($dirReplace,$replace,__DIR__);
        }
        elseif(preg_match("/(?i)(linux)/",$systeme))
        {
            $dirReplace = str_replace("\\","/",$dirReplace);
           return  str_replace($dirReplace,$replace,__DIR__);
        }
    }
    public static function AutoSaving()
    {
        spl_autoload_register(function($name){
            $className = str_replace("\\","/",$name);
            $file = self::ProviderFile("ProviderMain\LoadingClass","").$className.".php";
            $file = self::Os($file);
            if(file_exists($file))
            {
                require $file;
            }
            
        });
    }
    public static function Copy()
    {
        $file = Securite::require("databases",[],"written",false);
        $data = Securite::require("Database",[],"File.Database",false);
        $data = file_get_contents($data);
        file_put_contents($file,$data);
    }
}
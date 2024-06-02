<?php

namespace ProviderMain\Os;
class Os
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
           return str_replace($dirReplace,$replace,str_replace("ProviderMain\Os","",__DIR__));
        }
        elseif(preg_match("/(?i)(linux)/",$systeme))
        {
            $dirReplace = str_replace("\\","/",$dirReplace);
           return  str_replace($dirReplace,$replace,str_replace("ProviderMain/Os","",__DIR__));
        }
    }
}
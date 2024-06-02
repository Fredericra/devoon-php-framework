<?php

namespace ProviderMain\Database\Module;

use ProviderMain\SecuriteFile\Securite;
use ProviderMain\Database\DB\DB;


trait Relation {
    private static $config = [];
    protected static function getLine()
    {
        $file = Securite::require("Module", [], "File.Module", false);
        $file = json_decode(file_get_contents($file));
        $class = explode("\\", self::class)[array_key_last(explode("\\", self::class))];
        $parametre = [];
        foreach ($file as $key => $value) {
            $array = (array)$value;
            if(isset($array[$class]))
            {
                array_push($parametre,$value);
            }
        }
        if(isset($parametre[0]->$class))
        {
            self::$config = $parametre[0]->$class;
            return $parametre[0]->$class;
        }
    }
    protected static function getOther($class)
    {
        $file = Securite::require("Module", [], "File.Module", false);
        $file = json_decode(file_get_contents($file));
        $class = explode("\\", $class)[array_key_last(explode("\\", self::class))];
        $parametre = [];
        foreach ($file as $key => $value) {
            $array = (array)$value;
            if(isset($array[$class]))
            {
                array_push($parametre,$value);
            }
        }
        if(isset($parametre[0]->$class))
        {
            return $parametre[0]->$class;
        }
    }
    private static function MuchAs($class,string $relation)
    {
        $config = self::getLine();
        $configOther = self::getOther($class);
        $pdo = DB::Connecting();
        $db = $_ENV["DB_NAME"];
        $module  = self::$config;
        $table = $config->table;
        $table1 = $configOther->table;
        
        $resultat = $pdo->query("SELECT * FROM $db.$table INNER JOIN $db.$table1 ON $db.$table1.$relation = $db.$table.$relation")->fetchAll(); 
        return $resultat;
        
    }
}
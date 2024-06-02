<?php

namespace ProviderMain\Database\DB;

use ArrayAccess;
use PDO;
use ProviderMain\Error\ErrorValue;
use ProviderMain\SecuriteFile\Securite;

trait ConfigDb
{
    private static $tablename;
    protected static $PDO;
    public static function Accessing()
    {
      $env = file_get_contents(Securite::require("",[],"env",false)) ;
      $env = explode("\n",$env);
        foreach ($env as $key => $value) {
            $value = str_replace(";","",$value);
            $value=trim($value);
            if(strpos($value,"="))
            {
                list($index,$values) = explode("=",$value);
                $_ENV[trim($index)] = trim($values);
            }
        }
    }

    public static function Connecting()
    {
        self::Accessing();
     
        $serveur = trim($_ENV['DB_SERVEUR']);
        $host = trim($_ENV['DB_HOST']);
        $port = trim($_ENV['DB_PORT']);
        $user = trim($_ENV['DB_USER']);
        $pdo = new PDO("mysql:host=$host;port=$port",$user,"");
        $db = $_ENV['DB_NAME'];
        $pdo->query("CREATE DATABASE IF NOT EXISTS $db");
        self::$PDO = $pdo;
        return $pdo;
    }
    public static function listDb():array
    {
        $pdo = self::Connecting();
        $result = $pdo->query("SHOW DATABASES")->fetchAll();
        $array = [];
        foreach ($result as $key => $value) {
            array_push($array,$value['Database']);
        }
        return $array;
    }
    public static function listTable()
    {
        $pdo = self::Connecting();
        $db = $_ENV['DB_NAME'];
        $result = $pdo->query("SHOW TABLES in $db")->fetchAll();
        $array = [];
        foreach ($result as $key => $value) {
            array_push($array,$value['Tables_in_'.$db]);
        }
        return $array;
    }

    //add table

    public static function TableName($tablename)
    {
        if(!empty($tablename))
        {
            self::$tablename = $tablename;
        }
        else
        {
            ErrorValue::ErrorValue("le $tablename is empty",["fied database require table name"]);
        }
        return new self;
    }
   
}
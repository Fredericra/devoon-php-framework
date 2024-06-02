<?php

namespace ProviderMain\Console;

use ProviderMain\Database\DB\DB;
use ProviderMain\SecuriteFile\Securite;

class Database
{
    public static function Up()
    {
        Securite::require("Database", [], "File.Database");
    }
    public static function TableUp($code,$method,$flag)
    {
        self::Up();
        sleep(1);
            echo "table $method as create success".PHP_EOL;
    }
    public static function Table($code,$method,$flag)
    {
        $pdo = DB::Connecting();
        $db = $_ENV['DB_NAME'];
        if(in_array($method,DB::listTable()))
        {
            $pdo->query("DROP TABLE $db.$method");
            sleep(1);
            echo "table $method as refresh".PHP_EOL;
        }
    }
    public static function CreateTable()
    {
        $pdo =  DB::Connecting();
        $db = $_ENV['DB_NAME'];
        $dblist = DB::listDb();
        if (in_array($db, $dblist)) {
            $pdo->query("DROP DATABASE $db");
            sleep(1);
            echo "refresh $db access " . PHP_EOL;
            sleep(1);
            $pdo->query("CREATE DATABASE IF NOT EXISTS $db");
            echo "accessing $db and create new $db" . PHP_EOL;
        }
    }
    public static function Deleting()
    {
        $pdo =  DB::Connecting();
        $db = $_ENV['DB_NAME'];
        $dblist = DB::listDb();
        $pdo->query("DROP DATABASE IF EXISTS $db ");
        sleep(1);
        echo "defaulting the $db accesing" . PHP_EOL;
    }
}

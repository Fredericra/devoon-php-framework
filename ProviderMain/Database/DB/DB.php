<?php

namespace ProviderMain\Database\DB;

use ProviderMain\Database\StringConfig;
use ProviderMain\Error\ErrorValue;

class DB
{
    use ConfigDb,StringConfig;
    
    public static $config = [];
    public static function Where(string|array $array,string $table="")
    {
        if(empty($table))
        {

        }
        else
        {
            if(!empty(self::$tablename))
            {

            }
            else
            {
                
            }
        }
    }
    public static function Create(array $array)
    {
        $pdo = self::Connecting();
        $table = self::$tablename;
        $db = $_ENV["DB_NAME"];
        $id = self::ShowId($array,false);
        $relation  = self::FindArray('relation',$array);
        $id1 = self::ShowId($array,true);
        $coluim = [];
        if($id)
        {
            if(count($relation)===0)
            {
                $filter = self::FindArray(array_key_first($id1),$array,false);
                foreach ($filter as $key => $value) {
                    array_push($coluim,"$key ".self::getColuim($value));
                }
                $coluim = implode(",",$coluim);
                $valueid = self::ValueId($id1);
                $create = $pdo->query("CREATE TABLE IF NOT EXISTS $db.$table ($valueid,$coluim,beguinDate DATETIME)");
                array_push(self::$config,["id"=>array_key_first($id1),"table"=>$table]);
                if($create)
                {
                    sleep(1);
                    echo "create $table success".PHP_EOL;
                }
                
            }
            else
            {
                $filter = self::FindArray(array_key_first($id1),$array,false);
                $filter = self::FindArray("relation",$filter,false);
                $valueRelation = self::ValueRelation($relation);
                $valueid = self::ValueId($id1);
                foreach ($filter as $key => $value) {
                    array_push($coluim,"$key ".self::getColuim($value));
                }
                $coluim = implode(",",$coluim);
                $create = $pdo->query("CREATE TABLE IF NOT EXISTS $db.$table ($valueid,$coluim,$valueRelation,beguinDate Date)");
                array_push(self::$config,["id"=>array_key_first($id1),"table"=>$table]);
                if($create)
                {
                    sleep(1);
                    echo "create $table success".PHP_EOL;
                }
                
            }
        }
        else
        {
            ErrorValue::ErrorValue("entre id or key",["id =>key or id"]);
        }

       
    }
}
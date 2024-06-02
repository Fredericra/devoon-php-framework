<?php

namespace ProviderMain\Database;

use ProviderMain\Error\ErrorValue;
use ValueError;

trait StringConfig
{


    public static function TableColuim()
    {
    }
    public static function FindArray(array|string $find, array $array, $delete = true)
    {
        if (is_array($find)) {
            if ($delete) {
                return array_filter($array, function ($value, $index) use ($find) {
                    return in_array($index, $find);
                }, ARRAY_FILTER_USE_BOTH);
            } else {
                return array_filter($array, function ($value, $index) use ($find) {
                    return !in_array(strtolower($index), $find);
                }, ARRAY_FILTER_USE_BOTH);
            }
        } else {

            if ($delete) {
                return  array_filter($array, function ($value, $index) use ($find) {
                    return $index === $find;
                }, ARRAY_FILTER_USE_BOTH);
            } else {
                return  array_filter($array, function ($value, $index) use ($find) {
                    return $index !== $find;
                }, ARRAY_FILTER_USE_BOTH);
            }
        }
    }
    public static function getColuim(string $string)
    {
        $array = [];
        if (strpos($string, "|")) {
            $explode = explode("|", $string);
            foreach ($explode as $value) {
                if (preg_match("/^(?i)(text:|txt:)(\w+)$/", $value, $match)) {
                    $nbr = $match[2] > 250 ? 250 : $match[2];
                    array_push($array, "VARCHAR($nbr)");
                } elseif (preg_match("/^(?i)(int:|integer:)(\w+)$/", $value, $match)) {
                    $nbr = $match[2] > 250 ? 250 : $match[2];
                    array_push($array, "VARCHAR($nbr)");
                } elseif (preg_match("/^(?i)(longtext)$/", $value, $match)) {
                    array_push($array, "VARCHAR(250)");
                } elseif (preg_match("/^(?i)(json)$/", $value, $match)) {
                    array_push($array, "JSON");
                } elseif (preg_match("/^(?i)(date)$/", $value, $match)) {
                    array_push($array, "DATETIME");
                } elseif (preg_match("/^(?i)(not null)$/", $value, $match)) {
                    array_push($array, "NOT NULL");
                }
                elseif(preg_match("/^(?i)(bool|boolean)$/",$value))
                {
                    array_push($array,"boolean");
                }
                 else {
                    ErrorValue::ErrorValue("$string nout found", []);
                }
            }
        } else {
            if (preg_match("/^(?i)(text:|txt:)(\w+)$/", $string, $match)) {
                $nbr = $match[2] > 250 ? 250 : $match[2];
                array_push($array, "VARCHAR($nbr)");
            } elseif (preg_match("/^(?i)(int:|integer:)(\w+)$/", $string, $match)) {
                $nbr = $match[2] > 250 ? 250 : $match[2];
                array_push($array, "VARCHAR($nbr)");
            } elseif (preg_match("/^(?i)(longtext)$/", $string, $match)) {
                array_push($array, "VARCHAR(250)");
            } elseif (preg_match("/^(?i)(json)$/", $string, $match)) {
                array_push($array, "JSON");
            } elseif (preg_match("/^(?i)(date)$/", $string, $match)) {
                array_push($array, "DATETIME");
            } elseif (preg_match("/^(?i)(not null)$/", $string, $match)) {
                array_push($array, "NOT NULL");
            }
            elseif(preg_match("/^(?i)(bool|boolean)$/",$string))
            {
                array_push($array,"boolean");
            } else {
                ErrorValue::ErrorValue("$string nout found", []);
            }
        }
        return implode(" ", $array);
    }
    public static function ShowId($array, $type = true): bool|array
    {
        $array = array_filter($array, function ($value, $index) {
            return strtolower($value) === "key" || strtolower($value) === "id";
        }, ARRAY_FILTER_USE_BOTH);
        if ($type) {
            return $array;
        } else {
            return count($array) > 0 ? true : false;
        }
    }

    //return id value table
    public static function ValueId(array $array): string
    {
        $key = array_key_first($array);
        return $key . " int NOT NULL AUTO_INCREMENT,PRIMARY KEY ($key)";
    }

    public static function ValueRelation(array $array)
    {
        $key = array_key_first($array);
        $value = $array[$key];
        $push = [];
        if (strpos($value, "|")) {
            $explode = explode("|",$value);
            foreach ($explode as $key => $value) {
                if(preg_match("/^(?i)(table:|tablerelation:)(\w+)$/",$value,$match))
                {
                    $table = isset($match[2])?$match[2]:"";
                    array_push($push,["table"=>$table]); 
                }
                elseif(preg_match("/^(?i)(relationid:|id:|key:|:relation:)(\w+)$/",$value,$match))
                {
                    $id = isset($match[2])?$match[2]:"";
                    array_push($push,["id"=>$id]); 
                }
              
            }
        } else {
            ErrorValue::ErrorValue("$value config is null", ["table:??|id: or relation:??|key:"]);
        }
        $relation = (call_user_func_array("array_merge",$push));
        $id = $relation['id'];
        $table = $relation['table'];
        return $id." int, FOREIGN KEY ($id) REFERENCES $table($id)";
    }
}

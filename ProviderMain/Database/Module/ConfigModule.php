<?php

namespace ProviderMain\Database\Module;

use Error;
use ProviderMain\Database\DB\DB;
use ProviderMain\Error\ErrorValue;
use ProviderMain\Other\ArrayMethod;
use ProviderMain\SecuriteFile\Securite;
use ProviderMain\Validate\Validate;
use webApp\Model\User;

trait ConfigModule
{
    private static $param;
    private static $data = [];
    private static function PiloteTable()
    {
        $file = Securite::require("Module", [], "File.Module", false);
        $file = json_decode(file_get_contents($file));
        $class = explode("\\", self::class)[array_key_last(explode("\\", self::class))];
        $parametre = [];
        foreach ($file as $key => $value) {
            $array = (array)$value;
            if (isset($array[$class])) {
                array_push($parametre, $value);
            }
        }
        if (isset($parametre[0]->$class)) {
            self::$param = $parametre[0]->$class;
            return $parametre[0]->$class;
        }
    }
    public static function Delete(array|int $array)
    {
        self::PiloteTable();
        $pdo = DB::Connecting();
        $db = $_ENV['DB_NAME'];
        $configDb = DB::$config;
        $arrays = [];
        $config = self::$param;
        $table = $config->table;
        $permission = $config->authenticate;
        $id = array_filter($configDb, function ($value, $index) use ($table) {
            return $value['table'] === $table;
        }, ARRAY_FILTER_USE_BOTH);
        $id = count($id) > 0 ? call_user_func_array("array_merge", $id) : [];
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                array_push($arrays, $key . "='$value'");
            }
            $coluim = implode(" && ", $arrays);
            $result = $pdo->query("SELECT * FROM $db.$table WHERE $coluim")->fetchAll();
            if($result)
            {
                $pdo->query("DELETE  FROM $db.$table WHERE $coluim");
            }
            return $result;
        } else {
            $keys = $id['id'];
            $result =  $pdo->query("SELECT * FROM $db.$table WHERE $keys='$array'")->fetchAll();
            if ($result) {
                $pdo->query("DELETE  FROM $db.$table WHERE $keys='$array'");
            }
            return $result;
        }
    }
  
    public static function Create(array $array, $type = true)
    {

        self::PiloteTable();
        $pdo = DB::Connecting();
        $db = $_ENV['DB_NAME'];
        $configDb = DB::$config;
        $arrays = [];
        $config = self::$param;
        $table = $config->table;
        $permission = $config->authenticate;
        foreach ($array as $key => $value) {
            array_push($arrays, $key . "='$value'");
        }
        $id = array_filter($configDb, function ($value, $index) use ($table) {
            return $value['table'] === $table;
        }, ARRAY_FILTER_USE_BOTH);
        $idArray = call_user_func_array("array_merge", $id);
        $date = date('Y-m-d h:m:s');
        $coluim = implode(",", $arrays) . ",beguinDate='$date'";
        $error = Validate::ErrorAS();
        if (isset($error) && count($error) > 0) {
        } else {
            $pdo->query("INSERT INTO $db.$table SET $coluim");
            $id = $pdo->lastInsertId();
            return $id;
        }
    }
    protected static function ModuleIs(): object
    {
        self::PiloteTable();
        $pdo = DB::Connecting();
        $db = $_ENV['DB_NAME'];
        $configDb = DB::$config;
        $arrays = [];
        $config = self::$param;
        $table = $config->table;
        $permission = $config->authenticate;
        $id = "";
        $all = $pdo->query("SELECT * FROM $db.$table")->fetchAll();
        $written = Securite::require("databases",[],"written",false);
        $written = file_get_contents($written);
        preg_match("/(?i)(($table)(id)|(id)($table)|(id))(.*)(id|key)/",$written,$MatchFindId);
        $id = trim($MatchFindId[1]);
        return (object)["id" => $id, "table" => $table, "db" => $db, "pdo" => $pdo, "data" => $all, "permission" => $permission];
    }

    public static function idIs(array|int $id): object
    {
        $config = self::ModuleIs();
        $pdo = $config->pdo;
        $db = $config->db;
        $table = $config->table;
        $key = $config->id;
        $all = $config->data;
        $col = array_filter($all, function ($item, $index) use ($key, $id) {
            return is_array($id) ? in_array($item[$key], $id) : $item[$key] === $id;
        }, ARRAY_FILTER_USE_BOTH);
        $array = count($col) > 1 ? $col : $col[array_key_first($col)];
        return (object)$array;
    }
    public static function Where(array $data): object|bool
    {
        $config = self::ModuleIs();
        $objet = $config->data;
        $pdo = $config->pdo;
        $db = $config->db;
        $table = $config->table;
        $search = [];
        foreach ($data as $key => $value) {
            $search[] = $key . "='" . $value . "'";
        }
        $coluim = implode(" && ", $search);
        $result = $pdo->query("SELECT * FROM $db.$table WHERE $coluim ")->fetchAll();
        if (count($result) > 0) {
            if(count($result)===1)
            {
                $result = call_user_func_array("array_merge",$result);
                return (object)$result;
            }
            else
            {
                return (object)$result;
            }
        } else {
            return false;
        }
    }

    public static function Update(array $data)
    {
    }
    public static function Last()
    {
        $config = self::ModuleIs();
        $objet = $config->data;
        return (object)$objet[array_key_last($objet)];
    }
    public static function Fist(): object
    {

        $config = self::ModuleIs();
        $objet = $config->data;
        return (object)$objet[array_key_first($objet)];
    }
    public static function All(): object
    {
        $config = self::ModuleIs();
        return (object)$config->data;
    }
    public static function Authenticate()
    {
        $user = $_SESSION['user'];
        return (object)$user;
    }
    public static function Access(array|int|null $arrayId)
    {
        $config = self::ModuleIs();
        $objet = $config->data;
        $pdo = $config->pdo;
        $db = $config->db;
        $id = $config->id;
        $table = $config->table;
        $all = $config->data;
        $arrayId = $arrayId === null ? "" : $arrayId;
        if ($config->permission) {
            if (is_array($arrayId)) {
                foreach ($arrayId as $key => $value) {
                    $search[] = $key . "='" . $value . "'";
                }
                $coluim = implode(" && ", $search);
                $result = $pdo->query("SELECT * FROM $db.$table WHERE $coluim ")->fetchAll();
                $result = count($result) >= 1 ? $result[0] : [];
                $_SESSION["user"] = $result;
                $result !== [] ? $_SESSION['auth'] = true : $_SESSION['auth'] = false;
            } else {
                $result = $pdo->query("SELECT * FROM $db.$table WHERE $id='$arrayId'")->fetchAll();
                $result = count($result) >= 1 ? $result[0] : [];
                $_SESSION["user"] = $result;
                $result !== [] ? $_SESSION['auth'] = true : $_SESSION['auth'] = false;
            }
        } else {
            ErrorValue::ErrorValue("this" . self::class . "is invable permission", []);
        }
    }
    public static function Destroy(array|int|object $idData)
    {
        $config = self::ModuleIs();
        $objet = $config->data;
        $pdo = $config->pdo;
        $db = $config->db;
        $id = $config->id;
        $table = $config->table;
        $all = $config->data;
        $user = $_SESSION['user'];
        $auth = $_SESSION['auth'];
        $_SESSION['guest'][] = $user;
        if ($config->permission && $user) {
            if (is_array($idData)) {
                foreach ($idData as $key => $value) {
                    if ($user[$key] === $value) {
                        $_SESSION['auth'] = false;
                        unset($_SESSION['user']);
                    }
                }
            } elseif (is_object($idData)) {
                foreach ($idData as $key => $value) {
                    if ($user[$key] === $value) {
                        $_SESSION['auth'] = false;
                        unset($_SESSION['user']);
                    }
                }
            } else {
                if ($user[$id] === $idData) {
                    $_SESSION['auth'] = false;
                    unset($_SESSION['user']);
                } else {
                    ErrorValue::ErrorValue("nout found $id of $idData in auth", []);
                }
            }
        } else {
            ErrorValue::ErrorValue("this" . self::class . "is invable permission", []);
        }
    }
    public static function Verify(array $array, $type = true): bool
    {
        $config = self::ModuleIs();
        $objet = $config->data;
        $pdo = $config->pdo;
        $db = $config->db;
        $id = $config->id;
        $table = $config->table;
        $all = $config->data;
        $arrays = array_filter($array, function ($value, $index) {
            return !preg_match("/(?i)(password|pass|motspass|passwords)/", $index);
        }, ARRAY_FILTER_USE_BOTH);
        $password = array_filter($array, function ($value, $index) {
            return preg_match("/(?i)(password|pass|motspass|passwords)/", $index);
        }, ARRAY_FILTER_USE_BOTH);
        $mail = array_filter($array, function ($value, $index) {
            return preg_match("/(?i)(mail|email)/", $index);
        }, ARRAY_FILTER_USE_BOTH);
        $validate = Validate::ErrorAS();
        if ($config->permission) {
            foreach ($arrays as $key => $value) {
                $search[] = $key . "='" . $value . "'";
            }
            $passwords = $password[array_key_first($password)];
            $keypassword = array_key_first($password);
            $coluim = implode(" && ", $search);
            $result = $pdo->query("SELECT * FROM $db.$table WHERE $coluim")->fetchAll();
            $result = count($validate) === 0 ? $result : [];
            if (count($result) === 1 && count($validate) === 0) {
                $result = call_user_func_array("array_merge", $result);
                $passwordTable = $result[$keypassword];
                $emailKey = isset($mail) ? array_key_first($mail) : "";
                if (password_verify($passwords, $passwordTable) && $result[$emailKey] === $array[$emailKey]) {
                    $_SESSION['user'] = $result;
                    $_SESSION['auth'] = true;
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            ErrorValue::ErrorValue("$table is not permission authenticate");
        }
    }
}

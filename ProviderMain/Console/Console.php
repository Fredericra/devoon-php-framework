<?php

namespace ProviderMain\Console;

use DirectoryIterator;
use ProviderMain\SecuriteFile\Securite;

class Console
{
    public static function Trash($method, $key)
    {
        if ($key === "module") {
            self::trashModule($method);
        }
    }
    private static function trashModule($method)
    {
    }
    public static function Delete(array $array, $start = true)
    {
        if ($start) {
            return array_filter($array, function ($key) use ($array) {
                return $key !== array_key_last($array);
            }, ARRAY_FILTER_USE_KEY);
        } else {
            return array_filter($array, function ($key) use ($array) {
                return $key === array_key_last($array);
            }, ARRAY_FILTER_USE_KEY);
        }
    }
    public static function Module($create, $key, $flag)
    {
        if(!strpos($create,":"))
        {  
            if ($key === "model") {
                self::ModuleIs($create, $flag);
            }
            if ($key === "controller") {
                self::Controller($create,$flag);
            }
            if ($key === "middleware") {
                self::Middleware($create,$flag);
            }
            if ($key === "view") {
                self::View($create);
            }
            
        }
        else
        {
            echo "enter file name of $key".PHP_EOL;
        }
    }
    private static function View($create)
    {
        $controller = Securite::require("view", [], "written", false);
        $controller = file_get_contents($controller);
        $file = str_replace("ProviderMain/Console", "webApp/html", __DIR__);
        if (strpos($create, "/")) {
            $explode = explode("/", $create);
            $start = self::Delete($explode);
            $start = implode("/", $start);
            $filename = self::Delete($explode, false);
            $filename = implode("/", $filename);
            $filename1 = $filename . ".htm.php";
            $controller = str_replace("&&", $start, $controller);
            $controller = str_replace("??", $filename, $controller);
            $dir = $file . "/" . $start;
            $dirFile = $dir . "/" . $filename1;
            if (!is_dir($dir)) {
                mkdir($dir);
                if (!file_exists($dirFile)) {
                    file_put_contents($dirFile, $controller);
                    echo "$filename view as created" . PHP_EOL;
                } else {
                    echo "$filename view exists" . PHP_EOL;
                }
            } else {
                if (!file_exists($dirFile)) {
                    file_put_contents($dirFile, $controller);
                    echo "$filename view as created" . PHP_EOL;
                } else {
                    echo "$filename view exists" . PHP_EOL;
                }
            }
        } else {
            $name = $create . ".htm.php";
            $dir = $file . "/" . $name;
            if (!file_exists($dir)) {
                $controller = str_replace("&&", "welcome $create", $controller);
                $controller = str_replace("??", $create, $controller);
                file_put_contents($dir, $controller);
                echo "$create view as created" . PHP_EOL;
            } else {
                echo "$create view exists" . PHP_EOL;
            }
        }
    }
    private static function Middleware($create,$flag)
    {
        $middleware = Securite::require("middleware", [], "written", false);
        $middleware = file_get_contents($middleware);
        $file = str_replace("ProviderMain/Console", "File/Middleware", __DIR__);
        $json = Securite::require("Middleware", [], "File.Config", false);
        $namespace = "File\Middleware";
        $json1 = file_get_contents($json);
        $json1 = json_decode($json1);
        if (strpos($create, "/")) {
            $explode = explode("/", $create);
            $start = self::Delete($explode);
            $valueStart = implode("\\", $explode);
            $start = implode("/", $start);
            $filename = self::Delete($explode, false);
            $filename = implode("/", $filename);
            $nameMiddle = isset($flag["middle"])?$flag["middle"]:$filename;
            $filename1 = $filename . ".php";
            $middleware = str_replace("&&", $start, $middleware);
            $middleware = str_replace("??", $filename, $middleware);
            $dir = $file . "/" . $start;
            $dirFile = $dir . "/" . $filename1;
            if (!is_dir($dir)) {
                mkdir($dir);
                if (!file_exists($dirFile)) {
                    file_put_contents($dirFile, $middleware);
                    $new = [$nameMiddle => (object)["class" => $namespace . "\\" . $valueStart]];
                    $json1[] = $new;
                    $newDate = json_encode($json1, JSON_PRETTY_PRINT);
                    file_put_contents($json, $newDate);
                    echo "$nameMiddle middleware as created" . PHP_EOL;
                } else {
                    echo "$nameMiddle middleware exists" . PHP_EOL;
                }
            } else {
                if (!file_exists($dirFile)) {
                    file_put_contents($dirFile, $middleware);
                    $new = [$nameMiddle => (object)["class" => $namespace . "\\" . $valueStart]];
                    $json1[] = $new;
                    $newDate = json_encode($json1, JSON_PRETTY_PRINT);
                    file_put_contents($json, $newDate);
                    echo "$nameMiddle middleware as created" . PHP_EOL;
                } else {
                    echo "$nameMiddle middleware exists" . PHP_EOL;
                }
            }
        } else {
            $name = $create . ".php";
            $dir = $file . "/" . $name;
            $nameMiddle = isset($flag["middle"])?$flag["middle"]:$create;
            if (!file_exists($dir)) {
                $middleware = str_replace("\&&", "", $middleware);
                $middleware = str_replace("??", $create, $middleware);
                file_put_contents($dir, $middleware);
                $new = [$nameMiddle => (object)["class" => $namespace . "\\" . $create]];
                $json1[] = $new;
                $newDate = json_encode($json1, JSON_PRETTY_PRINT);
                file_put_contents($json, $newDate);
                echo "$nameMiddle middleware as created" . PHP_EOL;
            } else {
                echo "$nameMiddle middleware exists" . PHP_EOL;
            }
        }
    }
    private static function Controller($create,$flag)
    {
        $isGen = isset($flag["generate"]) && $flag["generate"]===true?"controllers":"controller";
        $controller = Securite::require($isGen, [], "written", false);
        $controller = file_get_contents($controller);
        $namespace = Securite::require("namespace",[],"namespace",false);
        $namespace1 = file_get_contents($namespace);
        $namespaceWrite = Securite::require("namespace",[],"written",false);
        $namespaceWrite1 = file_get_contents($namespaceWrite); 
        $file = str_replace("ProviderMain/Console", "webApp/Controller", __DIR__);
        if (strpos($create, "/")) {
            $explode = explode("/", $create);
            $start = self::Delete($explode);
            $start = implode("/", $start);
            $filename = self::Delete($explode, false);
            $filename = implode("/", $filename);
            $filename1 = $filename . ".php";
            $controller = str_replace("&&", $start, $controller);
            $controller = str_replace("??", $filename, $controller);
            $namespaceWrite1 = str_replace("??","webApp\\Controller\\$start",$namespaceWrite1);
            $dir = $file . "/" . $start;
            $dirFile = $dir . "/" . $filename1;
            if (!is_dir($dir)) {
                mkdir($dir);
                if (!file_exists($dirFile)) {
                    file_put_contents($dirFile, $controller);
                    if(!strpos($namespace1,$namespaceWrite1))
                    {
                        $new = $namespace1.PHP_EOL.$namespaceWrite1;
                        file_put_contents($namespace,$new);
                    }
                    echo "$filename controller as created" . PHP_EOL;
                } else {
                    echo "$filename controller exists" . PHP_EOL;
                }
            } else {
                if (!file_exists($dirFile)) {
                    file_put_contents($dirFile, $controller);
                    if(!strpos($namespace1,$namespaceWrite1))
                    {
                        $new = $namespace1.PHP_EOL.$namespaceWrite1;
                        file_put_contents($namespace,$new);
                    }
                    echo "$filename controller as created" . PHP_EOL;
                } else {
                    echo "$filename controller exists" . PHP_EOL;
                }
            }
        } else {
            $name = $create . ".php";
            $dir = $file . "/" . $name;
            $namespaceWrite1 = str_replace("??","webApp\\Controller",$namespaceWrite1);
            
            if (!file_exists($dir)) {
                $controller = str_replace("\&&", "", $controller);
                if(!strpos($namespace1,$namespaceWrite1))
                {
                    $new = $namespace1.PHP_EOL.$namespaceWrite1;
                    file_put_contents($namespace,$new);
                }
                $controller = str_replace("??", $create, $controller);
                file_put_contents($dir, $controller);
                echo "$create controller as created" . PHP_EOL;
            } else {
                echo "$create controller exists" . PHP_EOL;
            }
        }
    }
    private static function ModuleIs($create, $flag)
    {
        $tables = isset($flag["table"]) ? $flag["table"] : "user";
        $permission = isset($flag["permiss"]) && $flag["permiss"] === "true" ? true : false;
        $module = Securite::require("module", [], "written", false);
        $json = Securite::require("Module", [], "File.Module", false);
        $json1 = file_get_contents($json);
        $json1 = json_decode($json1);
        $file = str_replace("ProviderMain/Console", "webApp/Model", __DIR__);

        $module = file_get_contents($module);
        if (strpos($create, "/")) {
            $explode = explode("/", $create);
            $start = self::Delete($explode);
            $start = implode("/", $start);
            $filename = self::Delete($explode, false);
            $filename = implode("/", $filename);
            $filename1 = $filename . ".php";
            $module = str_replace("&&", $start, $module);
            $module = str_replace("??", $filename, $module);
            $dir = $file . "/" . $start;
            $dirFile = $dir . "/" . $filename1;
            if (!is_dir($dir)) {
                mkdir($dir);
                if (!file_exists($dirFile)) {
                    if (isset($tables)) {
                        $path = Securite::require("Database", [], "File.Database", false);
                        $path1 = file_get_contents($path);
                        $Create = file_get_contents(Securite::require("table", [], "written", false));
                        $new =  $path1 . PHP_EOL . str_replace("??", "$tables", $Create);
                        if(!strpos($path1,str_replace("??", "$tables", $Create)))
                        {
                            file_put_contents($path, $new);
                        }
                    }
                    file_put_contents($dirFile, $module);
                    $new = [$filename => (object)["table" => $tables, "authenticate" => $permission]];
                    $json1[] = $new;
                    $newDate = json_encode($json1, JSON_PRETTY_PRINT);
                    if(!array_keys($json1,$filename))
                    {
                        file_put_contents($json, $newDate);
                    }
                    echo "$filename modul as created" . PHP_EOL;
                } else {
                    echo "$filename module exists" . PHP_EOL;
                }
            } else {
                if (!file_exists($dirFile)) {
                    if (isset($tables)) {
                        $path = Securite::require("Database", [], "File.Database", false);
                        $path1 = file_get_contents($path);
                        $Create = file_get_contents(Securite::require("table", [], "written", false));
                        $new =  $path1 . PHP_EOL . str_replace("??", "$tables", $Create);
                        if(!strpos($path1,str_replace("??", "$tables", $Create)))
                        {
                            file_put_contents($path, $new);
                        }
                    }
                    file_put_contents($dirFile, $module);
                    $new = [$filename => (object)["table" => $tables, "authenticate" => $permission]];
                    $json1[] = $new;
                    $newDate = json_encode($json1, JSON_PRETTY_PRINT);
                    if(!array_keys($json1,$filename))
                    {
                        file_put_contents($json, $newDate);
                    }
                    echo "$filename modul as created" . PHP_EOL;
                } else {
                    echo "$filename module exists" . PHP_EOL;
                }
            }
        } else {
            $name = $create . ".php";
            $dir = $file . "/" . $name;
            if (!file_exists($dir)) {
                $module = str_replace("\&&", "", $module);
                $module = str_replace("??", $create, $module);
                file_put_contents($dir, $module);
                $new = [$create => (object)["table" => $create, "authenticate" => $permission]];
                $json1[] = $new;
                if (isset($tables)) {
                    $path = Securite::require("Database", [], "File.Database", false);
                    $path1 = file_get_contents($path);
                    $Create = file_get_contents(Securite::require("table", [], "written", false));
                    $new =  $path1 . PHP_EOL . str_replace("??", "$tables", $Create);
                    if(!strpos($path1,str_replace("??", "$tables", $Create)))
                    {
                        file_put_contents($path, $new);
                    }
                }
                $newDate = json_encode($json1, JSON_PRETTY_PRINT);
                file_put_contents($json, $newDate);
                echo "$create modul as created" . PHP_EOL;
            } else {
                echo "$create module exists" . PHP_EOL;
            }
        }
    }
}

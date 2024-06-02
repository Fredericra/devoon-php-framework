<?php

namespace ProviderMain\Console;

use ProviderMain\Array\Filter;
use ProviderMain\Os\Os;
use ProviderMain\SecuriteFile\Securite;
use Random\Engine\Secure;

class Cmd
{
    public static function Run($method,$flag)
    {
        if(isset($flag[0]) && isset($flag[1]))
        {
            $flag1 = $flag[0];
            $flag2 = $flag[1];
            $cmd = "npx -i tailwindcss $method/$flag1 -o $method/$flag2 --watch";
            shell_exec($cmd);
        }
    }
    public static function Console($dirFile, $type, $flag, $cmd)
    {
        $file = self::DirFile($type, $dirFile, $flag, $cmd);
    }

    //all overwrite dir return directory
    private static function getDirectoryParam($dirFile): array
    {

        if (strpos($dirFile, "/")) {
            $explodeFile = explode("/", $dirFile);
            $Dir  = Filter::Delete($explodeFile, true);
            $File = Filter::Delete($explodeFile, false);
            $FileName = implode("", $File);
            $dir = implode("/", $Dir);
            return ["filename" => $FileName, "dir" => $dir];
        } else {
            $FileName = $dirFile;
            return ["filename" => $FileName, "dir" => ""];
        }
    }
    private static function CreateNewFile($file)
    {
        var_dump($file);
    }
    private static function getType($type): bool
    {
        if (preg_match("/^(?i)(create)$/", $type)) {
            return true;
        } else {
            return false;
        }
    }
    private static function DirFile($type, $dirFile, $flag, $cmd)
    {
        $typeCmd = self::getType($cmd);
        $directory = Os::ProviderFile("ProviderMain/Console","");
        $controller = isset($flag["generate"]) ? "controllers" : "controller";
        $moduleWrite = isset($flag["generate"])?"modules":"module";
        $auth = isset($flag["permiss"]) ? true : false;
        $getFile = self::getDirectoryParam($dirFile);
        $table = isset($flag['table']) ? $flag['table'] : $getFile["filename"];
        $dirWay = isset($getFile['dir']) && !empty($getFile["dir"]) ? DIRECTORY_SEPARATOR . $getFile["dir"] : "";
        $filename = $getFile["filename"];
        $namespace = isset($getFile['dir']) && !empty($getFile['dir']) ? "\\" . str_replace("/", "\\", $getFile['dir']) : "";
        $namespaces = isset($getFile['dir']) && !empty($getFile['dir']) ? str_replace("/", "\\", $dirWay) : "";
        $middlewareName = isset($flag['middle']) ? $flag['middle'] : $filename;
        if (preg_match("/(?i)(model)/", $type, $match)) {
            $model = $directory . "webApp/Model" . $dirWay . "/$filename.php";
            $dirModel = $directory . "webApp/Model$dirWay";
            $data = (object)[$filename => (object)["table" => $table, "authenticate" => $auth]];
            $typeModul = $match[1];
            if ($typeCmd) {
                self::WriteFile([
                    Securite::require("Module", [], "File.Module", false) =>
                    $data,
                    Securite::require("Database", [], "File.Database", false) =>
                    str_replace("??", ucfirst($table), file_get_contents(Securite::require("table", [], "written", false)))
                ], $filename);
                self::CreateFile($model, $dirModel, str_replace("??", $filename, str_replace("&&", $namespaces, file_get_contents(Securite::require($moduleWrite, [], "written", false)))), $typeModul, $filename);
            } else {
            }
        } elseif (preg_match("/(?i)(controller)/", $type, $match)) {
            $typeController = $match[1];
            if ($typeCmd) {
                $controllers = $directory . "webApp/Controller" . $dirWay . "/$filename.php";
                $dirController = $directory . "webApp/Controller$dirWay";
                $written = str_replace("??", $filename, str_replace("&&", $namespaces, file_get_contents(Securite::require($controller, [], "written", false))));
                self::WriteFile([
                    Securite::requires("namespace","File/namespace","txt", false) =>
                    str_replace("??", "webApp/Controller$namespace/$filename", file_get_contents(Securite::require("namespace", [], "written", false)))
                ], $filename);
                self::CreateFile($controllers, $dirController, $written, $typeController, $filename);
            } else {
            }
        } elseif (preg_match("/(?i)(view)/", $type,$match)) {
            $typeController = $match[1];
            if ($typeCmd) {
                $views = $directory . "webApp/html" . $dirWay . "/$filename.htm.php";
                $dirView = $directory . "webApp/html$dirWay";
                $written = str_replace("??", $filename, str_replace("&&", $filename, file_get_contents(Securite::require("view", [], "written", false))));
                self::CreateFile($views, $dirView, $written, $typeController, $filename);
            } else {
            }
        } elseif (preg_match("/(?i)(middleware)/", $type,$match)) {
            $typeMiddle = $match[1];
            $middleware = $directory . "File/Middleware" . $dirWay . "/$filename.php";
            $dirMiddleware = $directory . "File/Middleware$dirWay";
            $class = "File\Middleware".str_replace("/","\\",$dirWay);
            $data = (object)[$middlewareName => (object)["class" => $class]];
            $written = str_replace("??", $filename, str_replace("&&", $namespaces, file_get_contents(Securite::require("middleware", [], "written", false))));
            if($typeCmd)
            {
                self::WriteFile([
                    Securite::require("Middleware", [], "File.Config", false) =>
                    $data,
                ], $filename);
                self::CreateFile($middleware, $dirMiddleware, $written, $typeMiddle, $filename);
            }
            else{

            }
            
        }
        elseif(preg_match("/(?i)(validate)/",$type,$match))
        {
            $systeme = php_uname("s");
            var_dump($systeme);
            $validateName = isset($flag["validate"])?$flag["validate"]:$filename;
            $rules = isset($flag["rule"])?$flag["rule"]:$filename;
            $validateDir = $directory."/"."File/Validate";
            if(!is_dir($validateDir))
            {
                mkdir($validateDir);
            }
            $data = (object)[$validateName=>"the :propery $rules"];
            $fileValidate = Securite::requires("validation.eng","File/Config","json",false);
        }
    }
    private static function WriteFile(array $data, string $filename)
    {
        if ($data > 0) {
            foreach ($data as $key => $value) {
                if (is_object($value)) {
                    $dir = $key;
                    $json = json_encode($value, JSON_PRETTY_PRINT);
                    $str = file_get_contents($key);
                    $array = json_decode($str);
                    $arrayValue = (array)$value;
                    foreach ($array as $key => $values) {
                        $returArray[] = (array)$values;
                    }
                    $returArray = call_user_func_array("array_merge", $returArray);
                    if (!array_key_exists($filename, $returArray)) {
                        $array[] = (object)$value;
                        $jsonDecode = json_encode($array, JSON_PRETTY_PRINT);
                        file_put_contents($dir, $jsonDecode);
                    }
                } else {
                    $file = file_get_contents($key);
                    $files = explode("\n", $file);
                    $values = str_replace("/", "\\", $value);
                    if (!in_array($values, $files)) {
                        $write = $file . "\n" . $values;
                        file_put_contents($key, $write);
                    }
                }
            }
        }
    }
    private static function CreateFile(string $directory, string $dir, string $copy, string $type, string $filename)
    {
        if (!is_dir($dir)) {
            $nameType = preg_match("/(?i)(view)/",$type)?"html":$type;
            preg_match("/(?i)(.*)($nameType)(.*)/", $dir, $match);
            $arrayFile = isset($match[3])?explode("/", $match[3]):[];
            if (count($arrayFile) > 2) {
                $linkDir = $match[1] . $match[2];
                $index = -1;
                $linkValue = [];
                foreach ($arrayFile as $key => $value) {
                    $index++;
                    $linkValue[] = $arrayFile[$index];
                    $dirReal = $linkDir . implode("/", $linkValue);
                    if (!is_dir($dirReal)) {
                        mkdir($dirReal);
                    }
                }
            } else {
                mkdir($dir);
            }
            self::verificate($directory, $copy, $type, $filename);
        } else {
            self::verificate($directory, $copy, $type, $filename);
        }
    }
    private static function verificate(string $directory, string $copy, string $type, string $filename)
    {
        if (file_exists($directory)) {
            sleep(1);
            echo "the $type $filename as exists" . PHP_EOL;
        } else {
            file_put_contents($directory, $copy);
            sleep(1);
            echo "the $type $filename as create" . PHP_EOL;
        }
    }
    private static function DeleteFile()
    {

    }
    private static function RewriteFile(array $json,string $filename)
    {

    }
}
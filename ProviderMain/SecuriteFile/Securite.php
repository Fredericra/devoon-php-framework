<?php

namespace ProviderMain\SecuriteFile;

use ProviderMain\Apirest\Request\Request;
use ProviderMain\Error\ErrorValue;
use ProviderMain\Os\Os;

class Securite
{
    public static function Style()
    {
        $uri = explode("/", $_SERVER['REQUEST_URI']);
        $data = [];
        foreach ($uri as $key => $value) {
            if ($key > 0) {
                $data[] = "..";
            }
        }
        $implode = implode("/", $data);
        echo "<link rel='stylesheet' href='$implode/ProviderMain/app/error.css'>";
        echo "<link rel='stylesheet' href='$implode/Public/base.css'>";
        
    }
    public static function Securite()
    {
        $file = str_replace("ProviderMain/SecuriteFile", "File", __DIR__);
        $provider = str_replace("ProviderMain/SecuriteFile", "ProviderMain", __DIR__);
        $providerFile = scandir($provider);
        $files = scandir($file);
        foreach ($files as $index => $dirFile) {
            if (preg_match("/^(?i)(config)$/", $dirFile)) {
                $dirConfig = scandir($file . "/" . $dirFile);
                $map = array_map(function ($fileConfig) use ($file, $dirFile) {
                    $dirFiles = $file . DIRECTORY_SEPARATOR . $dirFile . DIRECTORY_SEPARATOR . $fileConfig;
                    if (file_exists($dirFiles) && is_file($dirFiles)) {
                        return chmod($dirFiles, 0700);
                    }
                }, $dirConfig);
            }
        }
        array_map(function ($providers) use ($provider, $providerFile) {
            $searchFile = $provider . DIRECTORY_SEPARATOR . $providers;
            if (is_file($searchFile)) {
                chmod($searchFile, 0700);
            } else {
                $newDir = scandir($searchFile);
                array_map(function ($newfile) use ($searchFile) {
                    $newCode = $searchFile . DIRECTORY_SEPARATOR . $newfile;
                    if (is_file($newCode) && file_exists($newCode)) {
                        chmod($newCode,0400);
                    } else {
                        $newDir = scandir($newCode);
                        array_map(function ($newFileProvider) use ($newCode) {
                            $newfilesing = $newCode . DIRECTORY_SEPARATOR . $newFileProvider;
                            if(is_file($newfilesing) && file_exists($newfilesing))
                            {
                               chmod($newfilesing,0400);
                            }
                        }, $newDir);
                    }
                }, $newDir);
            }
        }, $providerFile);
    }
    private static function PathApp(string $path, string $file): string
    {
        if ($path === "view") {
            return "/webApp/html/$file.htm.php";
        }
        if ($path === "env") {
            return "/.env";
        }
        if ($path === "File.Config") {
            return "/File/Config/$file.json";
        }
        if ($path === "File.Module") {
            return "/File/Module/$file.json";
        }
        if ($path === "apirest") {
            return "/Apirest/$file.php";
        }
        if ($path === "File.Database") {
            return "/File/Database/$file.php";
        }
        if ($path === "written") {
            return "File/Written/$file.txt";
        }
        if ($path === "namespace") {
            return "File/namespace/$file.php";
        }
        if ($path === "Dashbord") {
            return "File/Dashbord/$file.json";
        }
       
    }
    public static function namespace(): array
    {
        $require = file(Securite::requires("namespace","File/namespace","txt", false));
        $namespace = "/(?i)(use )(.*)(;)/";
        foreach ($require as $key => $value) {
            if (preg_match($namespace, $value, $match)) {
                $explode = explode("\\", $match[2]);
                $class  = $explode[array_key_last($explode)];
                $arrayName = array_filter($explode, function ($value, $index) use ($explode) {
                    return $index !== array_key_last($explode);
                }, ARRAY_FILTER_USE_BOTH);
                $valuename = implode("\\", $arrayName);
                $arrayspace[] = ["class" => $class, "namespace" => $valuename];
            }
        }
        return $arrayspace;
    }
    public static function require(string $file, array $data = [], string $appdirectory = "view", $require = true)
    {
        $app = self::PathApp($appdirectory, $file);
        $files = Os::ProviderFile("ProviderMain".DIRECTORY_SEPARATOR."SecuriteFile",""). $app;
        if (file_exists($files)) {
            if ($require) {
                Request::getAll(['page' => $data]);
                $array = Request::array();
                require $files;
            } else {
                return $files;
            }
        } else {
            ErrorValue::Page404($file, []);
        }
    }
 
    private static function FindFile(string $dir,string $file,string $fileExtension)
    {
        $systeme = PHP_OS;
        $dirMain = Os::ProviderFile("ProviderMain".DIRECTORY_SEPARATOR."SecuriteFile","");
        $directory = $dirMain.DIRECTORY_SEPARATOR.$dir;
        $directory = Os::Os($directory);
        $directory =scandir($directory);
        $fileFind = $dirMain."/".$dir."/".$file.".$fileExtension";
        $filename = "";
       
            foreach ($directory as $key => $value) {
                $file = $dirMain."/".$dir."/".$value;
                if(is_file($file) && file_exists($file))
                {
                    if($file===$fileFind)
                    {
                        $filename = $file;
                    }
                }
            }
      
        return $filename;
    }
    public static function requires(string $file, string $appdirectory = "view",string $extension="php",$require = true,$data=[])
    {
        $app = self::FindFile($appdirectory, $file,$extension);
        if (file_exists($app)) {
            if ($require) {
                Request::getAll(['page' => $data]);
                $array = Request::array();
                require $app;
            } else {
                return $app;
            }
        } else {
            echo "file is not exists \n not found".PHP_EOL;
        }
    }
    
    public static function RedRoute()
    {
        $file = self::require("route", [], "written", false);
        $route = self::require("apirest", [], "apirest", false);
        file_put_contents($file, preg_replace("/(\<\?php)/", "", file($route)));
        $file = explode("\n", file_get_contents($file));
        foreach ($file as $key => $value) {
            var_dump($value);
        }
    }
}

<?php

namespace ProviderMain\Os;

use ProviderMain\Array\Filter;
use ProviderMain\SecuriteFile\Securite;

class WritterMod
{
    public static function Rewrite()
    {
        $systeme = PHP_OS;

        if (preg_match("/(?i)(winnt|win32)/", $systeme)) {
        } elseif (preg_match("/(?i)(linux)/", $systeme)) {
            $dir = Filter::Delete(explode("/", getcwd()), true);
            $directory = explode("/", getcwd());
            foreach ($directory as $key => $value) {
                if ($key < 2) {
                    $dirArray[] = $value;
                }
            }
            $implodeDir = implode(DIRECTORY_SEPARATOR,$dirArray);
            $patch = self::Auto("apache2/sites-available",["etc","bin"],$implodeDir);
            $write = str_replace("??",getcwd(),file_get_contents(Securite::requires("conf","File/Written","txt",false)));
            $explode = explode(DIRECTORY_SEPARATOR,getcwd());
            $end = Filter::Delete($explode,false);
            $appName =$patch.DIRECTORY_SEPARATOR.$end[array_key_first($end)].".conf";
            if(!file_exists($appName))
            {
                $command = "sudo chmod -R 777 $patch";
                shell_exec($command);
                file_put_contents($appName,$write);
            }
            else
            {
                $command = "sudo chmod -R 755 $patch";
                shell_exec($command);
            }
            $cmd = "sudo a2enmod rewrite";
            $cmd = "sudo systemctl restart apache2";
            shell_exec($cmd);
        }
    }
    private static function Auto(string $DIR,array $findDir,string $pathMain)
    {
        $index = -1;
        $user = scandir($pathMain);
        foreach ($user as $key => $value) {
            $dir = $pathMain.DIRECTORY_SEPARATOR.$value;
            $realPath = scandir($dir);
            foreach ($realPath as $keyPath => $valuePath) {
                if(in_array($valuePath,$findDir))
                {
                    $directory = $dir.DIRECTORY_SEPARATOR.$valuePath.DIRECTORY_SEPARATOR.$DIR;
                    if(is_dir($directory) && file_exists($directory))
                    {
                        return $directory;
                    }
                }
            }
        }
        $value = [];
       
    }

}

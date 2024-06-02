<?php
namespace ProviderMain\Storage;

use  ProviderMain\Os\Os;
class Storage
{
    private static string $dir;


    private static function Verify(string $name)
    {
        if(isset($_FILES[$name]))
        {
            return $_FILES[$name];
        }
        else
        {
            return null;
        }
    }
    public static function Filename(string $name,int $nbr=8)
    {
        $file = self::Verify($name);
        if(!is_null($file))
        {
            $type = pathinfo($file["name"],PATHINFO_EXTENSION);
            $name = pathinfo($file["name"],PATHINFO_FILENAME);
            $number = range(0,9);
            $letter = range("a","z");
            $suffle = substr(str_shuffle(implode("",$letter).implode("",$number)),0,$nbr).time();
            $new = str_replace($name,$suffle,$file['name']);
            return (object)["new"=>$new,"name"=>$file['name'],"directory"=>$file['tmp_name'],"error"=>$file['error'],"size"=>$file['size'],"type"=>$type,"path"=>$file['full_path']]; 
        }
    }
  
    public static function Share(string $share="")
    {
        $directory = Os::ProviderFile("","").DIRECTORY_SEPARATOR."Public".DIRECTORY_SEPARATOR."Share";
        if(!is_dir($directory))
        {
            mkdir($directory);
            self::ShareFile(["share"=>$share,"directory"=>$directory]);

        }
        else
        {
            self::ShareFile(["share"=>$share,"directory"=>$directory]);
        }
        self::$dir = !empty($share)?$directory.DIRECTORY_SEPARATOR.$share:$directory;
        return new self($directory);
    }
    private static function ShareFile(array $parama)
    {
        $dir = $parama["directory"];
        $share = $parama['share'];
        $directoryFile = $dir.DIRECTORY_SEPARATOR.$share;
        if(!empty($share))
        {
            if(!is_dir($directoryFile))
            {
                mkdir($directoryFile);
            }
        }
    }
    public static function Move(string $filename,bool $newName = true)
    {
        $file = self::Filename($filename);
        $tmp = $file->directory;
        if($newName)
        {
            self::upload($file,$filename,$newName);
        }
        else
        {
            self::upload($file,$filename,$newName);
        }
       
    }
    private static function upload(object|array $file,string $input,bool $type)
    {
        $fileUpload = $type?self::$dir.DIRECTORY_SEPARATOR.$file->new:$dir.DIRECTORY_SEPARATOR.$file->name;
        if(is_object($file))
        {
           $tmp = $file->directory;
          move_uploaded_file($_FILES[$input]['tmp_name'],$fileUpload);
        }
    }
    public static function delete(string|array $filename)
    {
        $dir = self::$dir;
        $scanDir = scandir($dir);
        foreach ($scanDir as $key => $value) {
            $newPath = $dir.DIRECTORY_SEPARATOR.$value;
            if(is_string($filename))
            {
                if(file_exists($newPath) && $value===$filename) 
                {
                    unlink($newPath);
                }
            }
            elseif(is_array($filename))
            {
                foreach ($filename as $keyFile => $valueFile) {
                    if(file_exists($newPath) && $valueFile===$filename) 
                    {
                        unlink($newPath);
                    }
                }
            }
        }
    }

}
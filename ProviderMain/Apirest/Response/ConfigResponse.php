<?php
namespace ProviderMain\Apirest\Response;

trait ConfigResponse
{
    private static $response = [];

    public static function getAll(array|string $data = []):void
    {
        if(isset($_GET))
        {
            array_push(self::$response,$data,$_GET);
        }
        elseif(isset($_POST))
        {
            array_push(self::$response,$data,$_GET);
        }
    }
    public static function All():object
    {
        $array = self::$response;
        return (object)(call_user_func_array('array_merge',$array));
    }
}
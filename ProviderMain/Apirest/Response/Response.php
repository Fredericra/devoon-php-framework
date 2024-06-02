<?php

namespace ProviderMain\Apirest\Response;

class Response
{
    public static $data = [];
    use ConfigResponse;
    public static function getData()
    {
        return self::$data;
    }

}
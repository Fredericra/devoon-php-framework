<?php

namespace ProviderMain\Error;

use ProviderMain\Apirest\Request\Request;
use ProviderMain\SecuriteFile\Securite;
use ProviderMain\Validate\ConfigValidate;

class Error
{
    use ConfigValidate;

    public static $message = [];

    private static function GetError()
    {
        $require = Securite::require("validate",[],"File.Config",false);
        $error = json_decode(file_get_contents($require,true));
        $_SERVER['REQUEST_METHOD']=="POST"?file_get_contents($require):file_put_contents($require,null);
        
    }
  
}
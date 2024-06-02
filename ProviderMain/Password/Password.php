<?php

namespace ProviderMain\Password;

class Password
{
    public static function Hash(string $password)
    {

        $option = [
            "cost" =>12
        ];
        return password_hash($password,PASSWORD_BCRYPT,$option);
    }
}
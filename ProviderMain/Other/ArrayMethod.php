<?php

namespace ProviderMain\Other;

class ArrayMethod
{
    public static function AntiDoubl(array|string $find,array $data,bool $type = true)
    {
        $result = [];
       if(is_array($find))
       {
          foreach ($data as $value) {
                foreach ($find as  $valueFind) {
                     $value[$valueFind] = $value;
                    var_dump($value);
                }
          }
       }
       else
       {

       }
       
    }
}
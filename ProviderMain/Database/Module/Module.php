<?php

namespace ProviderMain\Database\Module;



class Module  implements ModelInter
{

    public static function Create(array $array, $type = true)
    {
        //create new
    }
    public static function Relation(string $table, string $relation)
    {
        //relation table
    }
    public static function Delete(array|int $array)
    {
        //deleteing 
    }
    public static function ModuleRelation()
    {
        //$this->MuchAs(Module::class,"idUser");
    }
   
    
}
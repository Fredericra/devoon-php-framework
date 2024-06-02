<?php
namespace ProviderMain\Database\Module;

interface ModelInter
{
    public static function Create(array $array,$type=true);
    public static function Delete(array|int $array);
    public static function Relation(string $table,string $relation);
    public static function ModuleRelation();
}
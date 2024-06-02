<?php

namespace ProviderMain\Apirest\Controller;

interface InterfaceController
{
    public  function Id(string $id);
    public  function Show(array $array=[]);
    public function Store(...$array);
    public function Create(...$array);
    public function Destroy($id);
}
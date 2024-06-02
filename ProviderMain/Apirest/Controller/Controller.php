<?php

namespace ProviderMain\Apirest\Controller;

use ProviderMain\Apirest\Controller\InterfaceController;


class Controller implements InterfaceController
{
 
    public function __construct(protected $middleware,protected $route,protected $data)
    {
        
    }
    public function Id(string $id)
    {
        
    }
    public function Store(...$array)
    {
        
    }
    public function Show(array $array = [])
    {
        
    }
    public function Create(... $array)
    {
        
    }
    public function Destroy($id)
    {
        
    }
}
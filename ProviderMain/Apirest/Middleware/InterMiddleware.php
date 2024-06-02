<?php

namespace ProviderMain\Apirest\Middleware;

use ProviderMain\Apirest\Request\Request;
use ProviderMain\Apirest\Response\Response;
use ProviderMain\SecuriteFile\Securite;
use ProviderMain\Apirest\Route\RouteAs;
use ProviderMain\Error\ErrorValue;


class InterMiddleware
{
    public static function Configs()
    {
        $file = Securite::require("Middleware", [], "File.Config", false);
        $file = json_decode(file_get_contents($file));
        $middleware  = RouteAs::GetMiddle();
        $all = [];
        $allFile = [];
        foreach ($file as $keys => $files) {
            $array = (array)$files;
            $id = array_keys($array);
            array_push($all,$id);
            array_push($allFile,$array);

        }
        $all = call_user_func_array("array_merge",$all);
        $allFile = call_user_func_array("array_merge",$allFile);
        $uri = $_SERVER["REQUEST_URI"];
        $method = $_SERVER["REQUEST_METHOD"];
        foreach ($allFile as $key => $item) {
            foreach ($middleware as $index => $value) {
                foreach ($value['middleware'] as  $valueItem) {
                    if (in_array($valueItem,$all)) {
                        $uri = $value[0]['uri'];
                        $method = $value[0]['method'];
                        if ($method === $_SERVER['REQUEST_METHOD'] && $uri === $_SERVER['REQUEST_URI'] && $valueItem===$key) {
                            $middle = $item->class;
                            $middlewares = new $middle($value["route"],$key);
                            $request = new Request() ? new Request : new Response;
                            $other = new Request() ? new Request : new Response; 
                            $middlewares->Render($request, $other);
                        }
                    } elseif (empty($valueItem)) {
                    } else {
                        ErrorValue::ErrorValue("middleware $valueItem is not found", ["in File/Config/Middleware.json","$valueItem:{class??}"]);
                    }
                }
            }
        }
    }
}
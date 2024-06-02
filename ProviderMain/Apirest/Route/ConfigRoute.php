<?php

namespace ProviderMain\Apirest\Route;

use ProviderMain\Apirest\Request\Request;
use ProviderMain\Error\ErrorValue;
use ProviderMain\Other\ArrayMethod;
use ProviderMain\Apirest\Response\Response;
use ProviderMain\SecuriteFile\Securite;
use ProviderMain\Validate\Validate;

trait ConfigRoute
{
    protected static $method = [];
    protected static $RouteName = [];
    private static $config = [];
    protected static $route = [];
    protected static $api = [];
    public static $param = [];

    private static function verify(array|string $callback): string
    {
        if (is_array($callback)) {
            return $callback = implode("@", $callback);
        } else {
            if (strpos($callback, "@")) {
                return $callback;
            } else {
                ErrorValue::ErrorValue("the @ require in $callback", []);
            }
        }
    }
    public static function API(string $uri, string|array $callback)
    {
        Request::getAll($_POST);
        $callback = self::verify($callback);
        array_push(self::$config, [
            "method" => "API",
            "uri" => $uri,
            "call" => $callback
        ]);
        return new self(self::$config);
    }
    public static function POST(string $uri, string|array $callback)
    {
        Request::getAll($_POST);
        $callback = self::verify($callback);
        array_push(self::$config, [
            "method" => "POST",
            "uri" => $uri,
            "call" => $callback
        ]);
        return new self(self::$config);
    }
    //route methos
    public static function Method(string $method): bool
    {
        $route = self::$route;
        $filter = array_filter($route, function ($value, $index) use ($method) {
            return strtoupper($method) === $value[0]['method'] && $_SERVER['REQUEST_URI'] === $value[0]['uri'] && $_SERVER['REQUEST_METHOD'] === $value[0]['method'];
        }, ARRAY_FILTER_USE_BOTH);
        if (count($filter) === 0) {
            return false;
        } else {
            return true;
        }
    }
    public static function GET(string $uri, string|array $callback)
    {
        $callback = self::verify($callback);
        $method = [
            "method" => "GET",
            "uri" => $uri,
            "call" => $callback
        ];
        array_push(self::$config, $method);
        return new self($method);
    }
    public static function PUT(string $uri, string|array $callback)
    {
        $callback = self::verify($callback);
        $method = [
            "method" => "GET",
            "uri" => $uri,
            "call" => $callback
        ];
        array_push(self::$config, $method);
        return new self(self::$config);
    }
    public static function DELETE(string $uri, string|array $callback)
    {
        $callback = self::verify($callback);
        $method = [
            "method" => "GET",
            "uri" => $uri,
            "call" => $callback
        ];
        array_push(self::$config, $method);
        return new self(self::$config);
    }
    protected static function AntiDb(bool $type = true): array|bool
    {
        $method = self::$config;
        $result = [];
        foreach ($method as $value) {
            $find = $value['uri'] . "_" . $value['method'];
            if (isset($result[$find])) {
                $result['doubl'] = $value;
            } else {
                $result[$find] = $value;
            }
        }
        $return = isset($result['doubl']) ? $result['doubl'] : false;
        $bool = isset($result['doubl']) ? true : false;

        return $type === true ? $return : $bool;
    }
    private static function UriActif():array
    {
        $allRoute = self::AddNamespace();
        return array_map(function($value){
            $uri = $_SERVER['REQUEST_URI'];
            $route = $value['route'];
            $method = $value[0]['method'];
            $call = $value[0]['call'];
            $uriApi = $value[0]["uri"];
            $middleware = $value['middleware'];
            if(preg_match_all("/\{([^}]*)\}/",$uriApi,$match))
            {
                $indexMatch = $match[0];
                $indexMatching = $match[1];
                $explodeApi = explode("/",$uriApi);
                $explodeUri = explode("/",$uri);
                $fetch = [];
                if(count($explodeApi)===count($explodeUri))
                {
                   foreach ($explodeApi as $keyApi => $valueApi) {
                        foreach ($explodeUri as $keyUri => $valueUri) {
                            if($keyApi===$keyUri && preg_match("/\{([^}]*)\}/",$valueApi))
                            {
                                $other = str_replace(["{","}"],"",$valueApi);
                                Request::getAll([$other=>$valueUri]); 
                                $fetch[] = [$valueApi=>$valueUri];
                            }
                        }
                   }
                }
                $fetch = call_user_func_array("array_merge",$fetch);
                foreach ($explodeApi as $keys => $values) {
                    if(isset($fetch[$values]))
                    {
                        $explodeApi[$keys] = $fetch[$values];
                    }
                    else
                    {
                        $explodeApi[$keys] = $values;
                    }
                }
                $implodeUri = implode("/",$explodeApi);
                return $value = ["route"=>$route,"middleware"=>$middleware,["call"=>$call,"uri"=>$implodeUri,"method"=>$method]]; 
            }
            else
            {
                return $value;
            }
        },$allRoute);

    }
    private static function disRoute(): array|bool
    {
        $route = self::UriActif();
        $uri = explode("/",$_SERVER['REQUEST_URI']);
        $filter = array_filter($route, function ($value, $index)  {
            return $_SERVER['REQUEST_URI'] === $value[0]['uri'];
        }, ARRAY_FILTER_USE_BOTH);
        $uri = $_SERVER['REQUEST_URI'];
        count($filter) > 0 ?: ErrorValue::Page404($uri);
        $result = [];
        foreach ($route as $value) {
            $find = $value['route'];
            if (isset($result[$find])) {
                $result['doubl'] = $value;
            } else {
                $result[$find] = $value;
            }
        }
        return isset($result['doubl']) ? $result['doubl'] : false;
    }
    private static function AddNamespace(): array
    {
        $route = RouteAs::$route;
        $routes = RouteAs::GetMiddle();
        $maping = array_map(function($valueRoute) use($route){
            $uri = $valueRoute[0]['uri'];
            $method = $valueRoute[0]['method'];
            $middleware = $valueRoute['middleware'];
            $routename = $valueRoute['route'];
            foreach ($route as $key => $value) {
                $uris = $value[0]['uri'];
                $methods = $value[0]['method'];
                $calls = $value[0]['call'];
                if($uris===$uri && $methods===$method)
                {
                     $valueRoute = ["route" => $routename, "middleware" => $middleware, ["uri" => $uri, "call" => $calls, "method" => $method]];

                }
            }
            return $valueRoute;
        },$routes);
        return $maping;
    }

    private static function Render()
    {
        $route = self::UriActif();
        $uri = $_SERVER['REQUEST_URI'];
        $uriFind = explode("/",$uri);
        $method = $_SERVER['REQUEST_METHOD'];
        $array = [];
        foreach ($route as $key => $value) {
            $uriapi = $value[0]['uri'];
            if ($uri === $uriapi && $value[0]['method'] === $method) {
                $route = $value['route'];
                $middleware = $value['middleware'];
                $call =  explode("@", $value[0]['call']);
                $classCall = $call[0];
                $methodCall = $call[1];
                $request = Request::All();
                $controlleur = new $classCall($middleware,$route,$request);
                $request = new Request() ? new Request : new Response;
                $controlleur->$methodCall($request);
            }
        }
    }
    //param between of Route::view and Route::To
    private static function Param(string $routename, ...$data)
    {
        $route = self::UriActif();
        $array = array_filter($route, function ($value, $index) use ($routename) {
            return trim(str_replace(["'", '"'], "", $value['route'])) === $routename;
        }, ARRAY_FILTER_USE_BOTH);
        $uriActive = $_SERVER['REQUEST_URI'];
        if (count($array) > 0) {
            $array = call_user_func_array('array_merge', $array);
            $uri = $array[0]['uri'];
            preg_match_all("/\<([^}]*)\>/", $uri, $match);
            preg_match_all("/\{([^}]*)\}/", $uri, $matching);
            $data = is_array($data) && count($data) > 0 ? call_user_func_array("array_merge", $data) : [];
            $FindKey = isset($matching[0]) ? $matching[0] : [];
            $FindIndex = isset($matching[1]) ? $matching[1] : [];
            if (count($FindIndex) > 0) {
                if (count($data) >= count($FindKey)) {
                    $arrays = [];
                    foreach ($data as $key => $value) {
                        foreach ($FindKey as $keyIndex => $valueIndex) {
                            if ($key === $keyIndex) {
                                $arrays[] = [$valueIndex => $value];
                            }
                        }
                    }
                    $arrays = call_user_func_array("array_merge", $arrays);
                    Request::getAll($arrays);
                    $valeur = "";
                    $explode = explode("/", $uri);
                    foreach ($explode as $keys => $item) {
                        if (isset($arrays[$item])) {
                            $explode[$keys] = $arrays[$item];
                        } else {
                            $explode[$keys] = $item;
                        }
                    }
                    $url = implode("/", $explode);
                    $_GET['post'] = $url;
                    $_ENV['page'] =[$url,$arrays];
                    Response::$data = $uri;
                    return $url;
                } else {
                    ErrorValue::ErrorValue("params missing in route $routename", ["Route::to($routename,....)"]);
                }
            } else {
                $_ENV['page']="";
                return $array[0]['uri'];
            }
        }
    }
    public static function Run()
    {
        $doubl = self::AntiDb(true);
        $doubls = is_bool($doubl) ?: implode("", $doubl);
        $implode = is_array($doubl) ? implode($doubl) : "";
        if (!$doubl) {
            if (self::disRoute() === false) {
                self::Render();
            } else {
                $route = self::disRoute();
                ErrorValue::ErrorValue("route duplicate {$route['route']} is exists in apirest", ["diplicate route is exist"]);
            }
        } else {
            ErrorValue::ErrorValue("$implode is exists in apirest", [" $implode is exist"]);
        }
    }
}

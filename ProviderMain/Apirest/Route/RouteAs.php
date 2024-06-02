<?php

namespace ProviderMain\Apirest\Route;

use ArrayObject;
use File\Middleware\Middlewares;
use finfo;
use ProviderMain\Apirest\Middleware\InterMiddleware;
use ProviderMain\Apirest\Middleware\Middleware;
use ProviderMain\Apirest\Request\Request;
use ProviderMain\Apirest\Response\Response;
use ProviderMain\Autheticate\Admin;
use ProviderMain\Database\DB\DB;
use ProviderMain\Error\ErrorValue;
use ProviderMain\Guest\Guest;
use ProviderMain\SecuriteFile\Securite;
use ProviderMain\Twing\Twing;
use ProviderMain\Validate\Validate;
use ReflectionFunction;

use function PHPSTORM_META\override;

class RouteAs
{
    use ConfigRoute;

    public static $middleware = [];
    //return route name
    public static function name(string $routename)
    {
        array_push(self::$route, ["route" => $routename, end(self::$config)]);
        self::listRoute();
        return new self;
    }
    public static function To(string $routename, ...$data)
    {
        $uri = self::Param($routename, $data);
        //header("location:$uri");
        if ($uri !== $_SERVER['REQUEST_URI']) {
            echo "<script>
            window.location.replace('$uri');
        </script>";
        }
    }
    public static function View(string $routename, ...$data)
    {
        $uri = self::Param($routename, $data);
        self::$param[] = $uri;
        echo $uri;
    }

    public static function Is(string $routename): bool
    {
        $route = self::$route;
        $filter = array_filter($route, function ($value, $index) use ($routename) {
            return $value['route'] === $routename;
        }, ARRAY_FILTER_USE_BOTH);
        if (count($filter) === 0) {
            ErrorValue::ErrorValue("route $routename not found", [""]);
            exit();
        } else {
            $filter = call_user_func_array("array_merge", $filter);
            return  $filter['route'] === $routename && $filter[0]['uri'] === $_SERVER['REQUEST_URI'] ? true : false;
        }
    }
    private static function listRoute()
    {
        return self::$route;
    }

    public static function Page(string $page, array $data = [])
    {
        DB::Connecting();
        $route = self::RouteActif();
        $user = Admin::Auth();
        Request::getAll($data);
        $guest = Guest::All();
        ob_start();
        $error = count(Validate::$AddMessage) > 0 ? array_merge(Validate::ErrorAS(), Validate::$AddMessage) : Validate::ErrorAS();
        $request = Request::Array();
        $page = str_replace(".", "/", $page);
        Securite::require($page, $data, "view", true);
        extract(["uri" => $_SERVER['REQUEST_URI'],"guest"=>(object)$guest, "route" => $route, "user" => $user, "page" => (object)$data, "errors" => (object)$error, "response" => (object)Request::Array()]);
        foreach ($request as $key => $value) {
            extract([$key => $value]);
        }
        foreach ($_ENV as $keyEnv => $envItem) {
            extract([$keyEnv => $envItem]);
        }
        foreach ($data as $keys => $values) {
            extract([$keys => $values]);
        }
        $contenue = ob_get_clean();
        $namespace = Securite::namespace();
        $contenue = Twing::Render($contenue, $namespace);

        eval("?>$contenue");
    }
    protected static function Layout(string $page, array $data = [])
    {
        DB::Connecting();
        $route = self::RouteActif();
        $user = Admin::Auth();
        $guest = Guest::All();
        $error = count(Validate::$AddMessage) > 0 ? array_merge(Validate::ErrorAS(), Validate::$AddMessage) : Validate::ErrorAS();
        ob_start();
        $page = str_replace(".", "/", $page);
        $request = Request::Array();
        $page = str_replace(".", "/", $page);
        Securite::require($page, $data, "view", true);
        extract(["uri" => $_SERVER['REQUEST_URI'],"guest"=>(object)$guest, "route" => $route, "user" => $user, "page" => (object)$data, "errors" => (object)$error, "response" => (object)Request::Array()]);
        foreach ($request as $key => $value) {
            extract([$key => $value]);
        }
        foreach ($_ENV as $keyEnv => $envItem) {
            extract([$keyEnv => $envItem]);
        }
        foreach ($data as $keys => $values) {
            extract([$keys => $values]);
        }
        $contenue = ob_get_clean();
        $namespace = Securite::namespace();
        $contenue = Twing::Render($contenue, $namespace);

        eval("?>$contenue");
    }
    public static function RouteActif()
    {
        $route = self::GetMiddle();
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        $map = array_map(function ($value) {
            $method = str_replace(["'", '"'], "", $value[0]['method']);
            $uri = str_replace(["'", '"'], "", $value[0]['uri']);
            $call = str_replace(["'", '"'], "", $value[0]['call']);
            $middleware = str_replace(["'", '"'], "", $value['middleware']);
            $route = str_replace(["'", '"'], "", $value['route']);
            return $value = ["route" => $route, "middleware" => $middleware, "uri" => $uri, "call" => $call, "method" => $method];
        }, $route);
        $filter = array_filter($map, function ($value, $index) use ($uri, $method) {
            return $uri === $value["uri"] && $method === $value['method'];
        }, ARRAY_FILTER_USE_BOTH);
        $filter = call_user_func_array("array_merge", $filter);
        return (object)$filter;
    }
    protected static function GetListRoute()
    {
        $file = file(Securite::require("apirest", [], "apirest", false));
        $line = preg_replace("/(\<\?php)/", "", $file);
        $implodeLine = implode("", $line);
        $namespace = "/(?i)(use )(.*)(;)/";
        $regexSimple = "/(?i)(routeas::groupmiddle\((\"|')(\w+))(.*)\{([^}]*)\}/";
        $regexMiddle = "/(?i)(routeas::groupmiddle)(.*)\{([^}]*)\}(\);)/";
        $post = "/(?i)(routeas::)(post|get|put|API|delete\()\(([^)]*)\)(.*)((->name)\(([^)]*)\)|(->name)\(([^)]*)\)(->middleware)\(([^)]*)\))/";
        $midlePost = "/(?i)(routeas::)(post|get|api|put|delete\()\(([^)]*)\)(.*)(->name|->middleware)(\(([^)]*)\))/";
        $findMiddle = "/^(?i)(routeas::)(post|get|api|put|delete\()\(([^)]*)\)(.*)(->middleware)(\(([^)]*)\))$/";
        preg_match_all($namespace, $implodeLine, $match);
        preg_match_all($regexSimple, $implodeLine, $middle);
        preg_match_all($regexMiddle, $implodeLine, $FindGroup);
        preg_match_all($midlePost, $implodeLine, $Mroute);
        $middleArray = isset($middle[0]) && count($middle[0]) !== 0 ? $middle[0] : [];
        $routeGroup = isset($FindGroup[0]) && count($FindGroup[0]) !== 0 ? $FindGroup[0] : [];
        $routeAll = isset($Mroute[0]) && count($Mroute) !== 0 ? $Mroute[0] : [];
        $routeMiddle = [];
        $routes = [];
        $ending = [];
        $otherRoute = explode(";", $implodeLine);
        $mapRoute = array_map(function ($value) use ($post, $regexMiddle, $findMiddle, $midlePost) {
            preg_match($midlePost, $value, $matchPost);
            preg_match("/(?i)(\"([^\"]*)\"|\'([^']*)\')(,)((\[([^]]*)\])|(.*))/", $matchPost[3], $match2);
            preg_match("/(\w+)(::)(class,)(.*)/", $match2[7], $callingBack);
            preg_match($findMiddle, $value, $middlewareFind);
            $namespace = "";
            $list = Securite::namespace();
            if (count($callingBack) > 0) {
                foreach ($list as $items) {
                    if ($items['class'] === trim($callingBack[1])) {
                        $namespace = $items['namespace'] . "\\" . $items['class'] . "@" . str_replace(["'", '"'], "", trim($callingBack[4]));
                    }
                }
            } else {
                $namespace  = str_replace(["'", '"'], "", $match2[8]);
            }
            $array = [];
            $method = $matchPost[2];
            $route  = isset($matchPost[7]) ? $matchPost[7] : "";
            $uri = isset($match2[2]) ? $match2[2] : $match2[3];
            $middlewareIs = isset($middlewareFind[7]) ? str_replace(["'", '"'], "", $middlewareFind[7]) : "";
            return $value =  ["middleware" => [$middlewareIs, $middlewareIs], "route" => $route, ["call" => $namespace, "method" => $method, "uri" => $uri]];
        }, $routeAll);
        $map = array_map(function ($value) use ($mapRoute, $post, $regexMiddle, $regexSimple, $midlePost, $findMiddle) {
            preg_match_all($regexSimple, $value, $match);
            $middleware = str_replace(["'", '"'], "", $match[3][0]);
            preg_match_all($post, $value, $help);
            preg_match_all($midlePost, $value, $help1);
            $array = [];
            foreach ($help1[0] as $item) {
                $item = str_replace([";"], "", $item);
                preg_match($post, $item, $match1);
                preg_match($findMiddle, $item, $findMiddleware);
                $rountename = isset($match1[7]) ? $match1[7] : null;
                $middleware1 = isset($findMiddleware[7]) ? str_replace(["'", '"'], "", $findMiddleware[7]) : "";
                $method = isset($match1[2]) ? $match1[2] : null;
                $calling = isset($match1[3]) ? $match1[3] : null;
                preg_match("/(?i)(\"([^\"]*)\"|\'([^']*)\')(.*)/", $calling, $match2);
                $uri = str_replace(["'", '"'], "", $match2[1]);;
                $ClassIndex = isset($match2[4]) ? str_replace(["[", "]", ","], "", $match2[4]) : null;
                preg_match("/(.*)(\"([^\"]*)\"|\'([^']*)\'|@(\w+))/", $ClassIndex, $match3);
                preg_match($midlePost, $item, $middlePostFind);
                $allMiddle = isset($middleware1) ? [$middleware, $middleware1] : [$middleware, $middleware];
                $class = isset($match3[1]) ? $match3[1] : null;
                $indexing = isset($match3[0]) ? str_replace("::", "@", str_replace(["'", '"', "class"], "", $match3[0])) : null;
                array_push($array, ["middleware" => $allMiddle, "route" => $rountename, ["call" => $indexing, "method" => $method, "uri" => $uri]]);
            }

            return $value = $array;
        }, $routeGroup);
        $map = call_user_func_array("array_merge", $map);
        return self::RouteVerify($map, $mapRoute);
    }

    protected static function RouteVerify(array $array, array $arrays): array
    {

        $result = array_merge($array, $arrays);
        $db = [];
        foreach ($result as $key => $value) {
            $keys = $value['route'];
            if (isset($db[$keys])) {
                $db['db'] = $value;
            } else {
                $db[$keys] = $value;
            }
        }
        $filter = array_filter($db, function ($value, $index) {
            return $index !== "db";
        }, ARRAY_FILTER_USE_BOTH);
        $route = [];
        foreach ($filter as $key => $value) {
            array_push($route, $value);
        }
        self::$middleware = $route;
        return $route;
    }
    public static function GetMiddle(): array
    {
        return self::GetListRoute();
    }

    public static function GroupMiddle(string|array $type, $callback)
    {
        $reflection = new ReflectionFunction($callback);
        $source = $reflection->getFileName();
        $source = preg_replace("/(\<\?php)/", "", file($source));
        $callback();
       InterMiddleware::Configs();
    }
    public static function middleware(string $mode)
    {
        $array = [];
        array_push($array, end(self::$route));
        InterMiddleware::Configs();
    }
}

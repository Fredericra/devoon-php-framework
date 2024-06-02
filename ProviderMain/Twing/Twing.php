<?php

namespace ProviderMain\Twing;

class Twing
{
    public static function Render($value, $namespace)
    {
        $function = "/(?i)(@)(if|elseif|else|for|foreach|style|css|script|endScript|fileupload|title|endif|endfor|endforeach)(.*)/";
        $value = preg_replace_callback("/(?i)(v-)(date|value|dump)(.*)/", function ($match) use ($namespace) {

            $method = isset($match[2]) ? $match[2] : "";
            $return = isset($match[3]) ? $match[3] : "";
            $find = str_replace([$match[1] . $method], "", $match[0]);
            $AllValue = "";
            $objet = "/(\w+)(:{2})(\w+\(([^)]*)\))/";
            if (preg_match("/(?i)(value)/", $method)) {
                if (preg_match($objet, $return, $matching)) {
                    $valueReturn = "";
                    $class = isset($matching[1]) ? trim($matching[1]) : "";
                    $methodClass = isset($matching[3]) ? trim($matching[3]) : "";
                    $separator = isset($matching[2]) ? trim($matching[2]) : "";
                    $replace = $class . $separator . $methodClass;
                    foreach ($namespace as $item) {
                        if ($item['class'] === $class) {
                            $valueReturn = $item['namespace'] . "\\" . $class . $separator . $methodClass;
                        }
                    }
                    return "<?= $valueReturn ?>";
                }
                else
                {
                    $valueReturn = preg_match("/\(([^)]*)\)/",$return,$matching);
                    $findMatching = isset($matching[1])?$matching[1]:"";
                    return "<?= $findMatching ?>";
                }
            }
            if(preg_match("/(?i)(dump)/",$method))
            {
                if (preg_match($objet, $return, $matching)) {
                    $valueReturn = "";
                    $class = isset($matching[1]) ? trim($matching[1]) : "";
                    $methodClass = isset($matching[3]) ? trim($matching[3]) : "";
                    $separator = isset($matching[2]) ? trim($matching[2]) : "";
                    $replace = $class . $separator . $methodClass;
                    foreach ($namespace as $item) {
                        if ($item['class'] === $class) {
                            $valueReturn = $item['namespace'] . "\\" . $class . $separator . $methodClass;
                        }
                    }
                    return "<?php var_dump($valueReturn) ?>";
                }
                else
                {
                    $valueReturn = preg_match("/\(([^)]*)\)/",$return,$matching);
                    $findMatching = isset($matching[1])?$matching[1]:"";
                    return "<?php var_dump($findMatching) ?>";
                }
            }
            if(preg_match("/(?i)(date)/",$method))
            {
                $date = "date".$return;
               return  "<span class='link'><?php $date  ?></span>";
            }
        }, $value);
        $value = preg_replace_callback("/(?i)(m-)(layout)(.*)/", function ($match) use ($namespace) {
            $RouteAs = "RouteAs";
            $classSpace = "";
            $method = isset($match[2]) ? $match[2] : "";
            $return = isset($match[3]) ? $match[3] : "";
            foreach ($namespace as $item) {
                if ($item['class'] === $RouteAs) {
                    $classSpace = $item['namespace'] . "\\" . $RouteAs . "::Layout" . $return;
                }
            }
            return "<?php $classSpace  ?>";
        }, $value);
        $value = preg_replace_callback("/({{)(\s*(.*)\s*)(}})/", function ($match) use ($namespace) {
            $find = isset($match[0]) ? $match[0] : "";
            $find = str_replace(["{{", "}}"], "", $find);
            $start = isset($match[2]) ? $match[2] : "";
            $end = isset($match[3]) ? $match[3] : "";
            $objet = "/(\w+)(:{2})(\w+\(([^)]*)\))/";
            $findMethod = "/\(([^)]*)\)/";
            $startPhp = !empty($start) ? str_replace("{{", "<?php", $start) : "";
            $endtPhp = !empty($end) ? str_replace("}}", "?>", $end) : "";
            $allValue = [];
            if (!empty($startPhp) && !empty($endtPhp)) {
                if (preg_match($objet, $find, $findObjet)) {
                    $class = $findObjet[1];
                    $separator = $findObjet[2];
                    $method = $findObjet[3];
                    $replace = $class . $separator . $method;
                    $values = "";
                    foreach ($namespace as $namespaces) {
                        if (trim($class) === $namespaces['class']) {
                            $values = $namespaces['namespace'] . "\\" . trim($class) . trim($separator) . trim($method);
                        }
                    }
                    $find = str_replace($replace, $values, $find);
                }
                if ($find) {

                    return "<?=
                    $find
                     ?>";
                }
            }
        }, $value);
        $value = preg_replace_callback($function, function ($match) use ($namespace) {
            $find = $match[0];
            $pregFunction = "/(@)(?i)(\w+)((\(([^)]*)\))|())/";
            $objet = "/(\w+)(:{2})(\w+\(([^)]*)\))/";
            $findMethod = "/\(([^)]*)\)/";
            if (preg_match($pregFunction, $find, $finding)) {
                $find = str_replace("@", "", $find);
                $findFunction = isset($finding[4]) ? str_replace(["'", '"'], "", $finding[4]) : "";
                $method = isset($finding[2]) ? $finding[2] : "";
                if (preg_match("/(?i)(style|css)/", $method)) {
                    $findFunction = str_replace(["(",")"],"",$findFunction);
                    $base = [];
                    $uri = explode("/",$_SERVER['REQUEST_URI']);
                    foreach ($uri as $key => $value) {
                        if($key>1)
                        {
                            $base[] = "..";
                        }
                    }
                    $base = count($base)>0?implode("/",$base):"";
                    return "<link rel='stylesheet' href=$base/$findFunction>";
                }
                if (preg_match("/(?i)(title)/", $method)) {
                    $findFunction = str_replace(["(", ")","'",'"'],"", $findFunction);
                    $find1 = strpos($findFunction," ")?explode(" ",$findFunction):$findFunction;
                    if(preg_match("/(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]/",$findFunction)) {
                        return "<title> $findFunction </title>";
                    }
                    elseif(is_array($find1))
                    {
                     $imploe = implode(" ",$find1);   
                     return "<title> $imploe </title>";
                    }
                     else {
                        return "<title><?php echo $findFunction; ?></title>";
                    }
                }
                if (preg_match("/^(?i)(script)$/", $method) && !empty($findFunction)) {
                    $script = str_replace(["(",")"],"",$findFunction);
                    $uri = explode("/",$_SERVER['REQUEST_URI']);
                    $base = [];
                    foreach ($uri as $key => $value) {
                        if($key>1)
                        {
                            $base[] = "..";
                        }
                    }
                    $base = count($base)>0?implode("/",$base).DIRECTORY_SEPARATOR.$script:$script;
                    return "<script src='$base' type='module'></script>";
                }
                if(preg_match("/^(else)$/",$method))
                {
                    return "<?php $method: ?>";  
                }
                if(preg_match("/^(?i)(fileupload)$/",$method))
                {
                    
                    $value = "enctype='multipart/form-data'";
                    return "<?= $value ?>";
                }
                if (preg_match("/^(if|elseif|foreach|for)$/", $method, $start) && !empty($findFunction)) {
                    if (preg_match($objet, $find, $allFunction)) {
                        $class = trim($allFunction[1]);
                        $separator = trim($allFunction[2]);
                        $classMethod = trim($allFunction[3]);
                        $replace = $class . $separator . $classMethod;
                        $values = "";
                        foreach ($namespace as $item) {
                            if ($item['class'] === $class) {
                                $values = $item['namespace'] . "\\" . $class . $separator . $classMethod;
                            }
                        }
                        $find = str_replace($replace, $values, $find);
                    }
                    return "<?php $find: ?>";
                }
                if (preg_match("/^(endif|elseif|endforeach|for)$/", $method, $end)) {
                    $endi = $end[0];
                    return "<?php $endi ; ?>";
                }
            }
        }, $value);
        return $value;
    }
}

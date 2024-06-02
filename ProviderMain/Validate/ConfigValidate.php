<?php

namespace ProviderMain\Validate;

use ProviderMain\Apirest\Request\Request;
use ProviderMain\Database\DB\DB;
use ProviderMain\Database\Module\Module;
use ProviderMain\Error\ErrorValue;
use ProviderMain\SecuriteFile\Securite;
use ValueError;

trait ConfigValidate
{
    private static $error = [];
    public static function The(array $arrayValidate):void
    {
        $message = self::Validate($arrayValidate);
        $messageReturn  = self::Message($message);
        array_push(self::$error,$messageReturn);
        $value = json_encode($messageReturn);
        $validate = Securite::require("validate",[],"File.Config",false);
        
    }


  
    protected static function GetErrorValue():array
    {
        $require = json_decode(file_get_contents(Securite::require("langage",[],"File.Config",false)));
        $file = "validation.".$require[0]->validation;
        $validate = json_decode(file_get_contents(Securite::require("$file",[],"File.Config",false)));
        return (array)$validate[0];
        
    }
    protected static function Message(array $messageData):array
    {
        $messageArray = [];
        foreach ($messageData as $key => $item) {
            $value = $item['value'];
            $validate = $item['validate'];
            $property = $item['property'];
            $message = $item['message'];
            $other= $item['other'];
            $message = str_replace(":property",$property,$message);
            preg_match("/(\w+)(:)(\w+)/",$validate,$match)?$match:[];
            $index = count($match)>0?$match[3]:"";
            $message = str_replace(":$index",$other,$message);
            
            array_push($messageArray,self::Return($value,$validate,$message,$property,$other));
            
        }
        $filter = array_filter($messageArray,function($value,$index){
            return $value!==null;
        },ARRAY_FILTER_USE_BOTH);
        return array_merge($filter);
        
       
       
    }
    public static function GetMessage()
    {
        return self::$error;
    }
   

    private static function Return(string $value,string $validate,string $message,string $property,string $other)
    {
        $pdo = DB::Connecting();
        $db = $_ENV['DB_NAME'];
        $request = Request::All();
        if(preg_match("/(?i)(require)/",$validate) && empty($value))
        {
            return [$property=>["message"=>$message,"value"=>$value]]; 
        }
        elseif(preg_match("/(?i)(mixed|mixe)/",$validate) && !empty($value) && !preg_match("/(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]/",$value))
        {
            return [$property=>["message"=>$message,"value"=>$value]]; 
        }
        elseif(preg_match("/(?i)(mail|email)/",$validate) && !empty($value) && !filter_var($value,FILTER_VALIDATE_EMAIL))
        {
            return [$property=>["message"=>$message,"value"=>$value]]; 
        }
        elseif(preg_match("/(?i)(only:)(\w+)/",$validate) && !empty($value))
        {
            $tablelist = DB::listTable();
            if(in_array($other,$tablelist))
            {
                $select = $pdo->query("SELECT * FROM $db.$other WHERE $property = '$value' ")->fetchAll();
                if(count($select)!==0)
                {
                    return [$property=>["message"=>$message,"value"=>$value]]; 
                }
            }
            else
            {
                ErrorValue::ErrorValue("table $other not found",[]);
            }
        }
        elseif(preg_match("/(?i)(exists:)(\w+)/",$validate) && !empty($value))
        {
            $tablelist = DB::listTable();
            if(in_array($other,$tablelist))
            {
                $select = $pdo->query("SELECT * FROM $db.$other WHERE $property = '$value' ")->fetchAll();
                if(count($select)!==0)
                {
                }
                else
                {
                    return [$property=>["message"=>$message,"value"=>$value]]; 
                }
            }
            else
            {
                ErrorValue::ErrorValue("table $other not found",[]);
            }
        }
        elseif(preg_match("/(?i)(verifyhass:)(\w+)/",$validate) && !empty($value))
        {
            $tablelist = DB::listTable();
            $array = [];
            foreach ($_POST as $key => $value) {
                if(!preg_match("/(?i)(password|pass|mot_pass|keyword)/",$key))
                {
                    array_push($array,$key."='".$value."'");
                }
            }
           
        }
        elseif(preg_match("/(?i)(integer)/",$validate) && !empty($value) && !preg_match("/[0-9]/",$value))
        {
            return [$property=>["message"=>$message,"value"=>$value]]; 
        }
        elseif(preg_match("/(?i)(string)/",$validate) && !empty($value) && !preg_match("/[a-zA-Z]/",$value))
        {
            return ["property"=>["message"=>$message,"value"=>$value]]; 
        }
        elseif(preg_match("/(?i)(same:)(\w+)/",$validate) && !empty($value))
        {
            $otherValue = $request->$other;
            if($request->$property!==$otherValue)
            {
                return [$property=>["message"=>$message,"value"=>$value]]; 
            }
        }
        elseif(preg_match("/(?i)(file:)(\w+)/",$validate))
        {
            
        }
        elseif(preg_match("/(?i)(file:)(\d+)/",$validate) && !empty($value))
        {
            var_dump($validate);
        }
        elseif(preg_match("/(?i)(min:)(\w+)/",$validate) && !empty($value))
        {
            if(!is_integer($other))
            {
                if(strlen($value)<$other)
                {
                    return [$property=>["message"=>$message,"value"=>$value]]; 
                }
            }
            else
            {
                ErrorValue::ErrorValue("table $other is integer",[]);
            }
        }
        elseif(preg_match("/(?i)(max:)(\w+)/",$validate) && !empty($value))
        {
            if(!is_integer($other))
            { 
                if(strlen($value)>$other)  
                {
                    return [$property=>["message"=>$message,"value"=>$value]]; 
                }
            }
            else
            {
                ErrorValue::ErrorValue("table $other is integer",[]);
            }
        }
        elseif(preg_match("/(?i)(date)/",$validate) && !empty($value) &&!preg_match("/^(\d{4}-\d{2}-\d{2}|\d{2}-\d{2}-\d{4}|\d{2}-\d{2}-\d{4} \d{2}:\d{2}:\d{2}|\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})$/",$value))
        {
            return [$property=>["message"=>$message,"value"=>$value]]; 
        }
        elseif(preg_match("/(?i)(json)/",$validate) && !empty($value) && !is_object($value))
        {
            return [$property=>["message"=>$message,"value"=>$value]]; 
        }
        

    }

    private static function Validate(array|string $array)
    {
        $validate = self::GetErrorValue();
        $arrayValidate = [];
        $request = Request::All();
        
        foreach ($array as $key => $value) {
            foreach ($request as $keyRequest => $valueRequest) {
               if($key===$keyRequest)
               {
                    if(strpos($value,"|"))
                    {
                        $explode = explode("|",$value);
                        foreach ($explode as $keyExplode => $valueExplode) {
                            foreach ($validate as $keyValidate => $valueValidate) {
                                    if(preg_match("/(?i)$keyValidate/",$valueExplode))
                                    {
                                        array_push($arrayValidate,["property"=>$key,"value"=>$valueRequest,"message"=>$valueValidate,"validateValue"=>$valueExplode,"validate"=>$keyValidate,"other"=>""]); 
                                    }
                                    elseif(preg_match("/^(?i)(only:)(\w+)$/",$valueExplode,$match) && preg_match("/^(?i)(only:)(\w+)$/",$keyValidate,$match1) )
                                    {
                                        $other = $match[2];
                                        array_push($arrayValidate,["property"=>$key,"value"=>$valueRequest,"message"=>$valueValidate,"validateValue"=>$valueExplode,"validate"=>$keyValidate,"other"=>$other]); 
                                    }
                                    elseif(preg_match("/^(?i)(exists:)(\w+)$/",$valueExplode,$match) && preg_match("/^(?i)(exists:)(\w+)$/",$keyValidate,$match1) )
                                    {
                                        $other = $match[2];
                                        array_push($arrayValidate,["property"=>$key,"value"=>$valueRequest,"message"=>$valueValidate,"validateValue"=>$valueExplode,"validate"=>$keyValidate,"other"=>$other]); 
                                    }
                                    elseif(preg_match("/^(?i)(verifyhass:)(\w+)$/",$valueExplode,$match) && preg_match("/^(?i)(verifyhass:)(\w+)$/",$keyValidate,$match1) )
                                    {
                                        $other = $match[2];
                                        array_push($arrayValidate,["property"=>$key,"value"=>$valueRequest,"message"=>$valueValidate,"validateValue"=>$valueExplode,"validate"=>$keyValidate,"other"=>$other]); 
                                    }
                                    elseif(preg_match("/^(?i)(same:)(\w+)$/",$valueExplode,$match) && preg_match("/^(?i)(same:)(\w+)$/",$keyValidate,$match1) )
                                    {
                                        $other = $match[2];
                                        array_push($arrayValidate,["property"=>$key,"value"=>$valueRequest,"message"=>$valueValidate,"validateValue"=>$valueExplode,"validate"=>$keyValidate,"other"=>$other]); 
                                    }
                                    elseif(preg_match("/^(?i)(mixed:)(\w+)$/",$valueExplode,$match) && preg_match("/^(?i)(mixed:)(\w+)$/",$keyValidate,$match1) )
                                    {
                                        $other = $match[2];
                                        array_push($arrayValidate,["property"=>$key,"value"=>$valueRequest,"message"=>$valueValidate,"validateValue"=>$valueExplode,"validate"=>$keyValidate,"other"=>$other]); 
                                         
                                    }
                                    elseif(preg_match("/^(?i)(file:)(\d+)$/",$valueExplode,$match) && preg_match("/^(?i)(file:)(\w+)$/",$keyValidate,$match1) )
                                    {
                                        $other = $match[2];
                                        array_push($arrayValidate,["property"=>$key,"value"=>$valueRequest,"message"=>$valueValidate,"validateValue"=>$valueExplode,"validate"=>$keyValidate,"other"=>$other]); 
                                         
                                    }
                                    elseif(preg_match("/^(?i)(min:)(\w+)$/",$valueExplode,$match) && preg_match("/^(?i)(min:)(\w+)$/",$keyValidate,$match1) )
                                    {
                                        $other = $match[2];
                                        array_push($arrayValidate,["property"=>$key,"value"=>$valueRequest,"message"=>$valueValidate,"validateValue"=>$valueExplode,"validate"=>$keyValidate,"other"=>$other]); 
                                         
                                    }
                                    elseif(preg_match("/^(?i)(max:)(\w+)$/",$valueExplode,$match) && preg_match("/^(?i)(max:)(\w+)$/",$keyValidate,$match1) )
                                    {
                                        $other = $match[2];
                                        array_push($arrayValidate,["property"=>$key,"value"=>$valueRequest,"message"=>$valueValidate,"validateValue"=>$valueExplode,"validate"=>$keyValidate,"other"=>$other]); 
                                         
                                    }
                                    elseif(preg_match("/(?i)(date)/",$valueExplode) && preg_match("/(?i)(date)/",$keyValidate))
                                    {
                                        array_push($arrayValidate,["property"=>$key,"value"=>$valueRequest,"message"=>$valueValidate,"validateValue"=>$valueExplode,"validate"=>$keyValidate,"other"=>""]); 
                                    }
                                    elseif(preg_match("/(?i)(json)/",$valueExplode) && preg_match("/(?i)(json)/",$keyValidate))
                                    {
                                        array_push($arrayValidate,["property"=>$key,"value"=>$valueRequest,"message"=>$valueValidate,"validateValue"=>$valueExplode,"validate"=>$keyValidate,"other"=>""]); 
                                    }
                                    
                            }
                        }
                    }
                    else
                    {
                        foreach ($validate as $keyValidate => $valueValidate) {
                            if(preg_match("/(?i)$keyValidate/",$value))
                            {
                                array_push($arrayValidate,["property"=>$key,"value"=>$valueRequest,"message"=>$valueValidate,"validateValue"=>$value,"validate"=>$keyValidate,"other"=>""]); 
                            }
                            elseif(preg_match("/^(?i)(only:)(\w+)$/",$value,$match) && preg_match("/^(?i)(only:)(\w+)$/",$keyValidate,$match1) )
                            {
                                $other = $match[2];
                                array_push($arrayValidate,["property"=>$key,"value"=>$valueRequest,"message"=>$valueValidate,"validateValue"=>$value,"validate"=>$keyValidate,"other"=>$other]); 
                            }
                            elseif(preg_match("/^(?i)(verifyhass:)(\w+)$/",$value,$match) && preg_match("/^(?i)(verifyhass:)(\w+)$/",$keyValidate,$match1) )
                            {
                                $other = $match[2];
                                array_push($arrayValidate,["property"=>$key,"value"=>$valueRequest,"message"=>$value,"validateValue"=>$value,"validate"=>$keyValidate,"other"=>$other]); 
                            }
                            elseif(preg_match("/^(?i)(exists:)(\w+)$/",$value,$match) && preg_match("/^(?i)(only:)(\w+)$/",$keyValidate,$match1) )
                            {
                                $other = $match[2];
                                array_push($arrayValidate,["property"=>$key,"value"=>$valueRequest,"message"=>$valueValidate,"validateValue"=>$value,"validate"=>$keyValidate,"other"=>$other]); 
                            }
                            elseif(preg_match("/^(?i)(same:)(\w+)$/",$value,$match) && preg_match("/^(?i)(same:)(\w+)$/",$keyValidate,$match1) )
                            {
                                $other = $match[2];
                                array_push($arrayValidate,["property"=>$key,"value"=>$valueRequest,"message"=>$valueValidate,"validateValue"=>$value,"validate"=>$keyValidate,"other"=>$other]); 
                            }
                            elseif(preg_match("/^(?i)(mixed:)(\w+)$/",$value,$match) && preg_match("/^(?i)(mixed:)(\w+)$/",$keyValidate,$match1) )
                            {
                                $other = $match[2];
                                array_push($arrayValidate,["property"=>$key,"value"=>$valueRequest,"message"=>$valueValidate,"validateValue"=>$value,"validate"=>$keyValidate,"other"=>$other]); 
                                 
                            }
                            elseif(preg_match("/^(?i)(min:)(\w+)$/",$value,$match) && preg_match("/^(?i)(min:)(\w+)$/",$keyValidate,$match1) )
                            {
                                $other = $match[2];
                                array_push($arrayValidate,["property"=>$key,"value"=>$valueRequest,"message"=>$valueValidate,"validateValue"=>$value,"validate"=>$keyValidate,"other"=>$other]); 
                                 
                            }
                            elseif(preg_match("/^(?i)(max:)(\w+)$/",$value,$match) && preg_match("/^(?i)(max:)(\w+)$/",$keyValidate,$match1) )
                            {
                                $other = $match[2];
                                array_push($arrayValidate,["property"=>$key,"value"=>$valueRequest,"message"=>$valueValidate,"validateValue"=>$value,"validate"=>$keyValidate,"other"=>$other]); 
                                 
                            }
                            elseif(preg_match("/(?i)(date)/",$value) && preg_match("/(?i)(date)/",$keyValidate))
                            {
                                array_push($arrayValidate,["property"=>$key,"value"=>$valueRequest,"message"=>$valueValidate,"validateValue"=>$value,"validate"=>$keyValidate,"other"=>""]); 
                            }
                            elseif(preg_match("/(?i)(json)/",$value) && preg_match("/(?i)(json)/",$keyValidate))
                            {
                                array_push($arrayValidate,["property"=>$key,"value"=>$valueRequest,"message"=>$valueValidate,"validateValue"=>$value,"validate"=>$keyValidate,"other"=>""]); 
                            }
                           
                    }

                    }
               }
            }
        }
        return $arrayValidate;

    }
}
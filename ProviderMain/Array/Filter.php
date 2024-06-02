<?php

namespace ProviderMain\Array;

class Filter
{
    public static function Delete(array $array, $start = true):array
    {
        if ($start) {
            return array_filter($array, function ($key) use ($array) {
                return $key !== array_key_last($array);
            }, ARRAY_FILTER_USE_KEY);
        } else {
            return array_filter($array, function ($key) use ($array) {
                return $key === array_key_last($array);
            }, ARRAY_FILTER_USE_KEY);
        }
    }
    public static function FindArray(array|string $find, array $array, $delete = true)
    {
        if (is_array($find)) {
            if ($delete) {
                return array_filter($array, function ($value, $index) use ($find) {
                    return in_array($index, $find);
                }, ARRAY_FILTER_USE_BOTH);
            } else {
                return array_filter($array, function ($value, $index) use ($find) {
                    return !in_array(strtolower($index), $find);
                }, ARRAY_FILTER_USE_BOTH);
            }
        } else {

            if ($delete) {
                return  array_filter($array, function ($value, $index) use ($find) {
                    return $index === $find;
                }, ARRAY_FILTER_USE_BOTH);
            } else {
                return  array_filter($array, function ($value, $index) use ($find) {
                    return $index !== $find;
                }, ARRAY_FILTER_USE_BOTH);
            }
        }
    }
    public static function getArray(array $array):array
    {
        return $array;
    }

}
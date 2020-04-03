<?php
namespace Test\Utils;

class Functions
{
    public static function identity(): callable
    {
        return function ($arg) {
            return $arg;
        };
    }
}
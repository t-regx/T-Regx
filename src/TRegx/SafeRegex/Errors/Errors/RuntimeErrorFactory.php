<?php
namespace TRegx\SafeRegex\Errors\Errors;

class RuntimeErrorFactory
{
    public static function getLast(): RuntimeError
    {
        return new RuntimeError(\preg_last_error());
    }
}

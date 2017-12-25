<?php
namespace SafeRegex\Errors\Errors;

use SafeRegex\Errors\HostError;
use SafeRegex\PhpError;

abstract class CompileError implements HostError
{
    public static function get(): CompileError
    {
        $phpError = PhpError::getLast();

        if (is_callable('error_clear_last')) {
            return new StandardCompileError($phpError);
        }

        return new OvertriggerCompileError($phpError);
    }
}

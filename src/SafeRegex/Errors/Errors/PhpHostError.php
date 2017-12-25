<?php
namespace SafeRegex\Errors\Errors;

use SafeRegex\Errors\HostError;
use SafeRegex\PhpError;

abstract class PhpHostError implements HostError
{
    public static function get(): PhpHostError
    {
        $phpError = PhpError::getLast();

        if (is_callable('error_clear_last')) {
            return new StandardPhpHostError($phpError);
        }

        return new OvertriggerPhpHostError($phpError);
    }
}

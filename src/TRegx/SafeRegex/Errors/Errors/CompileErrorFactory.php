<?php
namespace TRegx\SafeRegex\Errors\Errors;

use TRegx\SafeRegex\PhpError;

class CompileErrorFactory
{
    public static function getLast(): CompileError
    {
        $phpError = PhpError::getLast();
        if (StandardCompileError::isCompatible()) {
            return new StandardCompileError($phpError);
        }
        return new OvertriggerCompileError($phpError);
    }
}

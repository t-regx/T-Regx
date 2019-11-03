<?php
namespace TRegx\SafeRegex\Errors\Errors;

use TRegx\SafeRegex\PhpError;

class CompileErrorFactory
{
    public static function getLast(): CompileError
    {
        $phpError = PhpError::getLast();
        if ($phpError === null) {
            return new StandardCompileError($phpError);
        }
        if (!CompileErrorFactory::isPregError($phpError)) {
            return new IrrelevantCompileError();
        }
        if (StandardCompileError::isCompatible()) {
            return new StandardCompileError($phpError);
        }
        return new OvertriggerCompileError($phpError);
    }

    private static function isPregError(PhpError $phpError): bool
    {
        return substr($phpError->getMessage(), 0, 5) === 'preg_';
    }
}

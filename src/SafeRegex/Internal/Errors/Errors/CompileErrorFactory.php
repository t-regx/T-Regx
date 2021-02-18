<?php
namespace TRegx\SafeRegex\Internal\Errors\Errors;

use TRegx\SafeRegex\Internal\PhpError;

class CompileErrorFactory
{
    public static function getLast(): CompileError
    {
        $error = \error_get_last();

        if ($error === null) {
            return new StandardCompileError(null);
        }

        $phpError = new PhpError($error['type'], $error['message']);

        if ($phpError->isPregError()) {
            return new StandardCompileError($phpError);
        }
        return new IrrelevantCompileError();
    }
}

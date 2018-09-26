<?php
namespace TRegx\SafeRegex\Errors\Errors;

use function error_clear_last;
use function is_callable;

class StandardCompileError extends CompileError
{
    public function occurred(): bool
    {
        return $this->error !== null;
    }

    public function clear(): void
    {
        error_clear_last();
    }

    public static function isCompatible(): bool
    {
        return is_callable('error_clear_last');
    }
}

<?php
namespace TRegx\SafeRegex\Errors\Errors;

class StandardCompileError extends CompileError
{
    public function occurred(): bool
    {
        return $this->getError() !== null;
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

<?php
namespace SafeRegex\Errors\Errors;

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
}

<?php
namespace TRegx\SafeRegex\Constants;

class PregConstants extends Constants
{
    protected function getConstants(): array
    {
        return [
            PREG_NO_ERROR              => 'PREG_NO_ERROR',
            PREG_BAD_UTF8_ERROR        => 'PREG_BAD_UTF8_ERROR',
            PREG_INTERNAL_ERROR        => 'PREG_INTERNAL_ERROR',
            PREG_BACKTRACK_LIMIT_ERROR => 'PREG_BACKTRACK_LIMIT_ERROR',
            PREG_RECURSION_LIMIT_ERROR => 'PREG_RECURSION_LIMIT_ERROR',
            PREG_BAD_UTF8_OFFSET_ERROR => 'PREG_BAD_UTF8_OFFSET_ERROR',
            PREG_JIT_STACKLIMIT_ERROR  => 'PREG_JIT_STACKLIMIT_ERROR',
        ];
    }

    protected function getDefault(): string
    {
        return 'UNKNOWN_PREG_ERROR';
    }
}

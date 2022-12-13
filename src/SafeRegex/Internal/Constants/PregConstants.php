<?php
namespace TRegx\SafeRegex\Internal\Constants;

class PregConstants
{
    public function getConstant(int $error): string
    {
        $constants = $this->getConstants();
        if (\array_key_exists($error, $constants)) {
            return $constants[$error];
        }
        return 'UNKNOWN_PREG_ERROR';
    }

    private function getConstants(): array
    {
        return [
            \PREG_NO_ERROR              => 'PREG_NO_ERROR',
            \PREG_BAD_UTF8_ERROR        => 'PREG_BAD_UTF8_ERROR',
            \PREG_INTERNAL_ERROR        => 'PREG_INTERNAL_ERROR',
            \PREG_BACKTRACK_LIMIT_ERROR => 'PREG_BACKTRACK_LIMIT_ERROR',
            \PREG_RECURSION_LIMIT_ERROR => 'PREG_RECURSION_LIMIT_ERROR',
            \PREG_BAD_UTF8_OFFSET_ERROR => 'PREG_BAD_UTF8_OFFSET_ERROR',
            \PREG_JIT_STACKLIMIT_ERROR  => 'PREG_JIT_STACKLIMIT_ERROR',
        ];
    }
}

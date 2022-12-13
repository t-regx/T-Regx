<?php
namespace TRegx\SafeRegex\Internal\Constants;

class PregMessages
{
    public function getConstant(int $error): string
    {
        $constants = $this->getConstants();
        if (\array_key_exists($error, $constants)) {
            return $constants[$error];
        }
        return 'Unknown error';
    }

    private function getConstants(): array
    {
        return [
            \PREG_NO_ERROR              => 'No error',
            \PREG_BAD_UTF8_ERROR        => 'Malformed UTF-8 characters, possibly incorrectly encoded',
            \PREG_INTERNAL_ERROR        => 'Internal error',
            \PREG_BACKTRACK_LIMIT_ERROR => 'Backtrack limit exhausted',
            \PREG_RECURSION_LIMIT_ERROR => 'Recursion limit exhausted',
            \PREG_BAD_UTF8_OFFSET_ERROR => 'The offset did not correspond to the beginning of a valid UTF-8 code point',
            \PREG_JIT_STACKLIMIT_ERROR  => 'JIT stack limit exhausted',
        ];
    }
}

<?php
namespace TRegx\SafeRegex\Internal\Constants;

class PhpErrorConstants
{
    public function getConstant(int $error): string
    {
        $constants = $this->getConstants();
        if (\array_key_exists($error, $constants)) {
            return $constants[$error];
        }
        return 'E_UNKNOWN_CODE';
    }

    private function getConstants(): array
    {
        return [
            \E_ERROR             => 'E_ERROR',
            \E_WARNING           => 'E_WARNING',
            \E_PARSE             => 'E_PARSE',
            \E_NOTICE            => 'E_NOTICE',
            \E_CORE_ERROR        => 'E_CORE_ERROR',
            \E_CORE_WARNING      => 'E_CORE_WARNING',
            \E_COMPILE_ERROR     => 'E_COMPILE_ERROR',
            \E_COMPILE_WARNING   => 'E_COMPILE_WARNING',
            \E_USER_ERROR        => 'E_USER_ERROR',
            \E_USER_WARNING      => 'E_USER_WARNING',
            \E_USER_NOTICE       => 'E_USER_NOTICE',
            \E_STRICT            => 'E_STRICT',
            \E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
            \E_DEPRECATED        => 'E_DEPRECATED',
        ];
    }
}

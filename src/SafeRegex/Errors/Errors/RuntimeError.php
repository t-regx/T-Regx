<?php
namespace SafeRegex\Errors\Errors;

use SafeRegex\Errors\HostError;

class RuntimeError implements HostError
{
    /** @var int */
    private $pregError;

    public function __construct(int $pregError)
    {
        $this->pregError = $pregError;
    }

    public function occurred(): bool
    {
        return $this->pregError !== PREG_NO_ERROR;
    }

    public function clear(): void
    {
        preg_match('//', '');
    }

    public static function getLast(): RuntimeError
    {
        return new RuntimeError(preg_last_error());
    }
}

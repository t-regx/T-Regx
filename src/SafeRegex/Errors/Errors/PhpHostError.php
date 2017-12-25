<?php
namespace SafeRegex\Errors\Errors;

use SafeRegex\Errors\HostError;
use SafeRegex\PhpError;

class PhpHostError implements HostError
{
    /** @var PhpError */
    private $error;

    public function __construct(PhpError $error)
    {
        $this->error = $error;
    }

    public function occurred(): bool
    {
        return $this->error !== null;
    }

    public function clear(): void
    {
        error_clear_last();
    }

    public static function get(): PhpHostError
    {
        return new PhpHostError(PhpError::fromArray(error_get_last()));
    }
}

<?php
namespace SafeRegex\Errors\Errors;

use SafeRegex\PhpError;

class StandardPhpHostError extends PhpHostError
{
    /** @var PhpError| */
    private $error;

    public function __construct(?PhpError $error)
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
}

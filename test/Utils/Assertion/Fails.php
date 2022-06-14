<?php
namespace Test\Utils\Assertion;

use AssertionError;
use Throwable;

trait Fails
{
    public function fail(): Throwable
    {
        $className = static::class;
        return new AssertionError("Failed to assert that $className wasn't used");
    }
}

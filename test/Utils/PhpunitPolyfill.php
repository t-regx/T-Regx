<?php
namespace Test\Utils;

use PHPUnit\Framework\Error\Error;
use PHPUnit\Framework\TestCase;

/**
 * Remove this class when PhpUnit7 is no longer used by T-Regx
 */
trait PhpunitPolyfill
{
    public function expectExceptionMessageMatches(string $message): void
    {
        if (!method_exists(TestCase::class, 'expectExceptionMessageMatches')) {
            parent::expectExceptionMessageRegExp($message);
        } else {
            parent::expectExceptionMessageMatches($message);
        }
    }

    public function expectError(): void
    {
        if (method_exists(TestCase::class, 'expectError')) {
            parent::expectError();
        } else {
            parent::expectException(Error::class);
        }
    }
}

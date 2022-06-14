<?php
namespace Test\Fakes\SafeRegex\Internal\Errors\Errors;

use Test\Utils\Assertion\Fails;
use TRegx\SafeRegex\Exception\JitStackLimitException;
use TRegx\SafeRegex\Exception\PregException;
use TRegx\SafeRegex\Internal\Errors\Errors\CompileError;

class JitStackError implements CompileError
{
    use Fails;

    public function occurred(): bool
    {
        throw $this->fail();
    }

    public function clear(): void
    {
        throw $this->fail();
    }

    public function getSafeRegexpException(string $methodName, $pattern): PregException
    {
        return new JitStackLimitException($pattern, $methodName, 0, '');
    }
}

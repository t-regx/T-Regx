<?php
namespace Test\Fakes\SafeRegex\Internal\Errors\Errors;

use Test\Utils\Assertion\Fails;
use TRegx\SafeRegex\Exception\PregException;
use TRegx\SafeRegex\Internal\Errors\Errors\CompileError;
use TRegx\SafeRegex\Internal\Errors\Errors\RuntimeError;

class ClearableError extends RuntimeError implements CompileError
{
    use Fails;

    /** @var bool */
    private $cleared = false;

    public function __construct()
    {
        parent::__construct(0);
    }

    public function occurred(): bool
    {
        throw $this->fail();
    }

    public function clear(): void
    {
        $this->cleared = true;
    }

    public function getSafeRegexpException(string $methodName, $pattern): PregException
    {
        throw $this->fail();
    }

    public function cleared(): bool
    {
        return $this->cleared;
    }
}

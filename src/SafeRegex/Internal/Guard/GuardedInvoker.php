<?php
namespace TRegx\SafeRegex\Internal\Guard;

use TRegx\SafeRegex\Exception\PregException;
use TRegx\SafeRegex\Exception\SuspectedReturnPregException;
use TRegx\SafeRegex\Internal\Errors\Errors\BothHostError;
use TRegx\SafeRegex\Internal\Errors\Errors\CompileError;
use TRegx\SafeRegex\Internal\Errors\Errors\EmptyHostError;
use TRegx\SafeRegex\Internal\Errors\Errors\IrrelevantCompileError;
use TRegx\SafeRegex\Internal\Errors\Errors\RuntimeError;
use TRegx\SafeRegex\Internal\Errors\Errors\StandardCompileError;
use TRegx\SafeRegex\Internal\Errors\HostError;
use TRegx\SafeRegex\Internal\Guard\Strategy\SuspectedReturnStrategy;
use TRegx\SafeRegex\Internal\PhpError;

class GuardedInvoker
{
    /** @var string */
    private $methodName;
    /** @var string|string[] */
    private $pattern;
    /** @var callable */
    private $callback;
    /** @var SuspectedReturnStrategy */
    private $strategy;

    public function __construct(string $methodName, $pattern, callable $callback, SuspectedReturnStrategy $strategy)
    {
        $this->methodName = $methodName;
        $this->pattern = $pattern;
        $this->callback = $callback;
        $this->strategy = $strategy;
    }

    public function catch(): array
    {
        $previousError = $this->joinedHostError(new RuntimeError(\preg_last_error()), $this->nullableError(\error_get_last()));
        if ($previousError->occurred()) {
            $previousError->clear();
        }
        $result = ($this->callback)();
        $followingError = $this->joinedHostError(new RuntimeError(\preg_last_error()), $this->nullableError(\error_get_last()));
        $exception = $this->pregException($followingError, $result);
        if ($followingError->occurred()) {
            $followingError->clear();
        }
        return [$result, $exception];
    }

    private function pregException(HostError $hostError, $pregResult): ?PregException
    {
        if ($hostError->occurred()) {
            return $hostError->getSafeRegexpException($this->methodName, $this->pattern);
        }
        if ($this->strategy->isSuspected($this->methodName, $pregResult)) {
            return new SuspectedReturnPregException($this->methodName, $this->pattern, \var_export($pregResult, true));
        }
        return null;
    }

    private function joinedHostError(RuntimeError $runtime, CompileError $compile): HostError
    {
        if ($runtime->occurred() && $compile->occurred()) {
            return new BothHostError($compile, $runtime);
        }
        if ($compile->occurred()) {
            return $compile;
        }
        if ($runtime->occurred()) {
            return $runtime;
        }
        return new EmptyHostError();
    }

    private function nullableError(?array $error): CompileError
    {
        if ($error === null) {
            return new StandardCompileError(null);
        }
        return self::mapToError($error['type'], $error['message']);
    }

    private function mapToError(int $type, string $message): CompileError
    {
        $phpError = new PhpError($type, $message);
        if ($phpError->isPregError()) {
            return new StandardCompileError($phpError);
        }
        return new IrrelevantCompileError();
    }
}

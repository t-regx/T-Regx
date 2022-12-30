<?php
namespace TRegx\SafeRegex\Internal\Guard;

use TRegx\SafeRegex\Exception\PregException;
use TRegx\SafeRegex\Exception\SuspectedReturnPregException;
use TRegx\SafeRegex\Internal\Errors\CompileError;
use TRegx\SafeRegex\Internal\Errors\NoError;
use TRegx\SafeRegex\Internal\Errors\RuntimeError;
use TRegx\SafeRegex\Internal\Errors\StandardCompileError;
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
        [$result, $compile, $runtime] = $this->execute();
        if ($runtime->occurred()) {
            $runtime->clear();
        }
        return [$result, $this->pregException($compile, $runtime, $result)];
    }

    private function execute(): array
    {
        $compileError = new NoError();
        \set_error_handler(function (int $type, string $message) use (&$compileError) {
            if ($type === \E_WARNING) {
                $phpError = new PhpError($type, $message);
                if ($phpError->isPregError()) {
                    $compileError = new StandardCompileError($phpError);
                    return true;
                }
            }
            return false;
        });
        try {
            $result = ($this->callback)();
        } finally {
            \restore_error_handler();
        }
        return [$result, $compileError, new RuntimeError(\preg_last_error())];
    }

    private function pregException(CompileError $compile, RuntimeError $runtime, $pregResult): ?PregException
    {
        if ($compile->occurred()) {
            return $compile->getSafeRegexpException($this->methodName, $this->pattern);
        }
        if ($runtime->occurred()) {
            return $runtime->getSafeRegexpException($this->methodName, $this->pattern);
        }
        if ($this->strategy->isSuspected($this->methodName, $pregResult)) {
            return new SuspectedReturnPregException($this->methodName, $this->pattern, \var_export($pregResult, true));
        }
        return null;
    }
}

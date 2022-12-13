<?php
namespace TRegx\SafeRegex\Internal\Guard;

use TRegx\SafeRegex\Exception\PregException;
use TRegx\SafeRegex\Exception\SuspectedReturnPregException;
use TRegx\SafeRegex\Internal\Errors\ErrorsCleaner;
use TRegx\SafeRegex\Internal\Guard\Strategy\SuspectedReturnStrategy;

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
    /** @var ErrorsCleaner */
    private $errorsCleaner;

    public function __construct(string $methodName, $pattern, callable $callback, SuspectedReturnStrategy $strategy)
    {
        $this->methodName = $methodName;
        $this->pattern = $pattern;
        $this->callback = $callback;
        $this->strategy = $strategy;
        $this->errorsCleaner = new ErrorsCleaner();
    }

    public function catch(): array
    {
        $this->errorsCleaner->clear();
        $result = ($this->callback)();
        $exception = $this->retrieveGlobals($this->methodName, $result);
        $this->errorsCleaner->clear();

        return [$result, $exception];
    }

    private function retrieveGlobals(string $methodName, $pregResult): ?PregException
    {
        $hostError = $this->errorsCleaner->getError();
        if ($hostError->occurred()) {
            return $hostError->getSafeRegexpException($methodName, $this->pattern);
        }
        if ($this->strategy->isSuspected($methodName, $pregResult)) {
            return new SuspectedReturnPregException($methodName, $this->pattern, \var_export($pregResult, true));
        }
        return null;
    }
}

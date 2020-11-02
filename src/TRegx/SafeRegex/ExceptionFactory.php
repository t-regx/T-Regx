<?php
namespace TRegx\SafeRegex;

use TRegx\SafeRegex\Errors\ErrorsCleaner;
use TRegx\SafeRegex\Exception\Factory\SuspectedReturnPregExceptionFactory;
use TRegx\SafeRegex\Exception\PregException;
use TRegx\SafeRegex\Guard\Strategy\SuspectedReturnStrategy;

class ExceptionFactory
{
    /** @var string|array */
    private $pattern;
    /** @var SuspectedReturnStrategy */
    private $strategy;
    /** @var ErrorsCleaner */
    private $errorsCleaner;
    /** @var SuspectedReturnPregExceptionFactory */
    private $exceptionFactory;

    public function __construct($pattern, SuspectedReturnStrategy $strategy, ErrorsCleaner $errorsCleaner)
    {
        $this->pattern = $pattern;
        $this->strategy = $strategy;
        $this->errorsCleaner = $errorsCleaner;
        $this->exceptionFactory = new SuspectedReturnPregExceptionFactory();
    }

    public function retrieveGlobals(string $methodName, $pregResult): ?PregException
    {
        $hostError = $this->errorsCleaner->getError();
        if ($hostError->occurred()) {
            return $hostError->getSafeRegexpException($methodName, $this->pattern);
        }
        if ($this->strategy->isSuspected($methodName, $pregResult)) {
            return $this->exceptionFactory->create($methodName, $this->pattern, $pregResult);
        }
        return null;
    }
}

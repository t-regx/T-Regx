<?php
namespace TRegx\SafeRegex;

use TRegx\SafeRegex\Errors\ErrorsCleaner;
use TRegx\SafeRegex\Exception\Factory\SuspectedReturnPregExceptionFactory;
use TRegx\SafeRegex\Exception\PregException;
use TRegx\SafeRegex\Guard\Strategy\SuspectedReturnStrategy;

class ExceptionFactory
{
    /** @var SuspectedReturnStrategy */
    private $strategy;
    /** @var ErrorsCleaner */
    private $errorsCleaner;
    /** @var SuspectedReturnPregExceptionFactory */
    private $exceptionFactory;

    public function __construct(SuspectedReturnStrategy $strategy, ErrorsCleaner $errorsCleaner)
    {
        $this->strategy = $strategy;
        $this->errorsCleaner = $errorsCleaner;
        $this->exceptionFactory = new SuspectedReturnPregExceptionFactory();
    }

    /**
     * @param string $methodName
     * @param mixed $pregResult
     * @return PregException|null
     */
    public function retrieveGlobals(string $methodName, $pregResult): ?PregException
    {
        $hostError = $this->errorsCleaner->getError();
        if ($hostError->occurred()) {
            return $hostError->getSafeRegexpException($methodName);
        }
        return $this->getExceptionByReturnValue($methodName, $pregResult);
    }

    private function getExceptionByReturnValue(string $methodName, $pregResult): ?PregException
    {
        if ($this->strategy->isSuspected($methodName, $pregResult)) {
            return $this->exceptionFactory->create($methodName, $pregResult);
        }
        return null;
    }
}

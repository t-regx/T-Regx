<?php
namespace TRegx\SafeRegex;

use TRegx\SafeRegex\Errors\ErrorsCleaner;
use TRegx\SafeRegex\Exception\Factory\SuspectedReturnSafeRegexExceptionFactory;
use TRegx\SafeRegex\Exception\SafeRegexException;
use TRegx\SafeRegex\Guard\Strategy\SuspectedReturnStrategy;

class ExceptionFactory
{

    /** @var SuspectedReturnStrategy */
    private $strategy;
    /** @var ErrorsCleaner */
    private $errorsCleaner;

    public function __construct(SuspectedReturnStrategy $strategy)
    {
        $this->strategy = $strategy;
        $this->errorsCleaner = new ErrorsCleaner();
    }

    /**
     * @param string $methodName
     * @param mixed  $pregResult
     * @return SafeRegexException|null
     */
    public function retrieveGlobals(string $methodName, $pregResult): ?SafeRegexException
    {
        $hostError = $this->errorsCleaner->getError();
        if ($hostError->occurred()) {
            return $hostError->getSafeRegexpException($methodName);
        }
        return $this->getExceptionByReturnValue($methodName, $pregResult);
    }

    private function getExceptionByReturnValue(string $methodName, $pregResult): ?SafeRegexException
    {
        if ($this->strategy->isSuspected($methodName, $pregResult)) {
            return (new SuspectedReturnSafeRegexExceptionFactory())->create($methodName, $pregResult);
        }
        return null;
    }
}

<?php
namespace TRegx\SafeRegex\Internal;

use TRegx\SafeRegex\Exception\PregException;
use TRegx\SafeRegex\Exception\SuspectedReturnPregException;
use TRegx\SafeRegex\Internal\Errors\ErrorsCleaner;
use TRegx\SafeRegex\Internal\Guard\Strategy\SuspectedReturnStrategy;

class ExceptionFactory
{
    /** @var string|array */
    private $pattern;
    /** @var SuspectedReturnStrategy */
    private $strategy;
    /** @var ErrorsCleaner */
    private $errorsCleaner;

    public function __construct($pattern, SuspectedReturnStrategy $strategy, ErrorsCleaner $errorsCleaner)
    {
        $this->pattern = $pattern;
        $this->strategy = $strategy;
        $this->errorsCleaner = $errorsCleaner;
    }

    public function retrieveGlobals(string $methodName, $pregResult): ?PregException
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

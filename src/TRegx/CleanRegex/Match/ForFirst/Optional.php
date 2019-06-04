<?php
namespace TRegx\CleanRegex\Match\ForFirst;

use TRegx\CleanRegex\Exception\CleanRegex\CleanRegexException;
use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;

interface Optional
{
    /**
     * @param string $exceptionClassName
     * @return mixed
     * @throws \Throwable|SubjectNotMatchedException
     */
    public function orThrow(string $exceptionClassName = CleanRegexException::class);

    /**
     * @param mixed $substitute
     * @return mixed
     */
    public function orReturn($substitute);

    /**
     * @param callable $substituteProducer
     * @return mixed
     */
    public function orElse(callable $substituteProducer);
}

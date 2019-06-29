<?php
namespace TRegx\CleanRegex\Match\ForFirst;

use TRegx\CleanRegex\Exception\CleanRegex\CleanRegexException;
use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;

interface Optional
{
    /**
     * Please keep in mind, the default $exceptionClassName is not the exact exception
     * that's going to be thrown in the case of an empty Optional. Other implementations
     * of `TRegx\CleanRegex\Match\ForFirst\Optional` have different default class names.
     *
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

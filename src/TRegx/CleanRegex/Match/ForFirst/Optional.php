<?php
namespace TRegx\CleanRegex\Match\ForFirst;

use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;

interface Optional
{
    /**
     * @param string $exceptionClassName
     * @return mixed
     * @throws \Throwable|SubjectNotMatchedException
     */
    public function orThrow(string $exceptionClassName = SubjectNotMatchedException::class);

    /**
     * @param mixed $default
     * @return mixed
     */
    public function orReturn($default);

    /**
     * @param callable $producer
     * @return mixed
     */
    public function orElse(callable $producer);
}

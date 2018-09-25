<?php
namespace TRegx\CleanRegex\Match\ForFirst;

use Throwable;
use TRegx\CleanRegex\Exception\CleanRegex\NotMatched\Subject\FirstMatchMessage;
use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\UnknownSignatureExceptionFactory;
use TRegx\CleanRegex\Match\Details\NotMatched;

class NotMatchedOptional implements Optional
{
    /** @var array */
    private $matches;
    /** @var string */
    private $subject;

    public function __construct(array $matches, string $subject)
    {
        $this->matches = $matches;
        $this->subject = $subject;
    }

    /**
     * @param string $exceptionClassName
     * @throws Throwable
     */
    public function orThrow(string $exceptionClassName = SubjectNotMatchedException::class)
    {
        $exception = $this->getException($exceptionClassName);
        throw $exception;
    }

    private function getException(string $exceptionClassName): Throwable
    {
        return (new UnknownSignatureExceptionFactory($exceptionClassName, new FirstMatchMessage()))->create($this->subject);
    }

    /**
     * @param mixed $default
     * @return mixed
     */
    public function orReturn($default)
    {
        return $default;
    }

    /**
     * @param callable $producer
     * @return mixed
     */
    public function orElse(callable $producer)
    {
        return call_user_func($producer, new NotMatched($this->matches, $this->subject));
    }
}

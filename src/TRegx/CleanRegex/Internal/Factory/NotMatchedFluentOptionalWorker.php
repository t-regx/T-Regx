<?php
namespace TRegx\CleanRegex\Internal\Factory;

use Throwable;
use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\SignatureExceptionFactory;

class NotMatchedFluentOptionalWorker implements NotMatchedWorker
{
    /** @var NotMatchedMessage */
    private $message;
    /** @var array */
    private $arguments;

    public function __construct(NotMatchedMessage $message, ...$arguments)
    {
        $this->message = $message;
        $this->arguments = $arguments;
    }

    public function orThrow(string $exceptionClassName): Throwable
    {
        $factory = new SignatureExceptionFactory($exceptionClassName, $this->message);
        if (empty($this->arguments)) {
            return $factory->createWithoutSubject();
        }
        return $factory->create(...$this->arguments);
    }

    public function orElse(callable $producer)
    {
        return $producer();
    }
}

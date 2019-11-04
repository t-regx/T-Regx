<?php
namespace TRegx\CleanRegex\Internal\Factory;

use Throwable;
use TRegx\CleanRegex\Exception\CleanRegex\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\SignatureExceptionFactory;
use TRegx\CleanRegex\Internal\Subjectable;

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

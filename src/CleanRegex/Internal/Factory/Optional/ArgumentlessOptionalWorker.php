<?php
namespace TRegx\CleanRegex\Internal\Factory\Optional;

use Throwable;
use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\SignatureExceptionFactory;

class ArgumentlessOptionalWorker implements OptionalWorker
{
    /** @var NotMatchedMessage */
    private $message;
    /** @var string */
    private $defaultExceptionClassname;

    public function __construct(NotMatchedMessage $message, string $defaultExceptionClassname)
    {
        $this->message = $message;
        $this->defaultExceptionClassname = $defaultExceptionClassname;
    }

    public function orThrow(?string $exceptionClassName): Throwable
    {
        $factory = new SignatureExceptionFactory($exceptionClassName ?? $this->defaultExceptionClassname, $this->message);
        return $factory->createWithoutSubject();
    }

    public function orElse(callable $producer)
    {
        return $producer();
    }
}

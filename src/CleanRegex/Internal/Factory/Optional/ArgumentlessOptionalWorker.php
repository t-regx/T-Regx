<?php
namespace TRegx\CleanRegex\Internal\Factory\Optional;

use Throwable;
use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\SignatureExceptionFactory;

class ArgumentlessOptionalWorker implements OptionalWorker
{
    /** @var SignatureExceptionFactory */
    private $exceptionFactory;
    /** @var string */
    private $fallbackClassname;

    public function __construct(NotMatchedMessage $message, string $defaultExceptionClassname)
    {
        $this->exceptionFactory = new SignatureExceptionFactory($message);
        $this->fallbackClassname = $defaultExceptionClassname;
    }

    public function orElse(callable $producer)
    {
        return $producer();
    }

    public function orThrow(?string $exceptionClassname): Throwable
    {
        return $this->exceptionFactory->createWithoutSubject($exceptionClassname ?? $this->fallbackClassname);
    }
}

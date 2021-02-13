<?php
namespace TRegx\CleanRegex\Internal\Factory\Optional;

use Throwable;
use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\SignatureExceptionFactory;
use TRegx\CleanRegex\Internal\Subjectable;

class SubjectOptionalWorker implements OptionalWorker
{
    /** @var NotMatchedMessage */
    private $message;
    /** @var Subjectable */
    private $subjectable;
    /** @var string */
    private $defaultExceptionClassname;

    public function __construct(NotMatchedMessage $message, Subjectable $subjectable, string $defaultExceptionClassname)
    {
        $this->message = $message;
        $this->subjectable = $subjectable;
        $this->defaultExceptionClassname = $defaultExceptionClassname;
    }

    public function orElse(callable $producer)
    {
        return $producer();
    }

    public function orThrow(?string $exceptionClassName): Throwable
    {
        return (new SignatureExceptionFactory($exceptionClassName ?? $this->defaultExceptionClassname, $this->message))
            ->create($this->subjectable->getSubject());
    }
}

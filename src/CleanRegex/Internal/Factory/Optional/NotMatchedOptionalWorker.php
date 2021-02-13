<?php
namespace TRegx\CleanRegex\Internal\Factory\Optional;

use Throwable;
use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\SignatureExceptionFactory;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Details\NotMatched;

class NotMatchedOptionalWorker implements OptionalWorker
{
    /** @var NotMatchedMessage */
    private $message;
    /** @var Subjectable */
    private $subjectable;
    /** @var NotMatched */
    private $notMatched;
    /** @var string */
    private $defaultExceptionClassname;

    public function __construct(NotMatchedMessage $message,
                                Subjectable $subjectable,
                                NotMatched $notMatched,
                                string $defaultExceptionClassname)
    {
        $this->message = $message;
        $this->subjectable = $subjectable;
        $this->notMatched = $notMatched;
        $this->defaultExceptionClassname = $defaultExceptionClassname;
    }

    public function orThrow(?string $exceptionClassName): Throwable
    {
        return (new SignatureExceptionFactory($exceptionClassName ?? $this->defaultExceptionClassname, $this->message))->create($this->subjectable->getSubject());
    }

    public function orElse(callable $producer)
    {
        return $producer($this->notMatched);
    }
}

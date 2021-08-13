<?php
namespace TRegx\CleanRegex\Internal\Factory\Optional;

use Throwable;
use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\SignatureExceptionFactory;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Details\NotMatched;

class NotMatchedOptionalWorker implements OptionalWorker
{
    /** @var SignatureExceptionFactory */
    private $exceptionFactory;
    /** @var Subjectable */
    private $subjectable;
    /** @var NotMatched */
    private $notMatched;
    /** @var string */
    private $fallbackClassname;

    public function __construct(NotMatchedMessage $message,
                                Subjectable $subjectable,
                                NotMatched $notMatched,
                                string $fallbackClassname)
    {
        $this->exceptionFactory = new SignatureExceptionFactory($message);
        $this->subjectable = $subjectable;
        $this->notMatched = $notMatched;
        $this->fallbackClassname = $fallbackClassname;
    }

    public function orElse(callable $producer)
    {
        return $producer($this->notMatched);
    }

    public function orThrow(?string $exceptionClassname): Throwable
    {
        return $this->exceptionFactory->create($exceptionClassname ?? $this->fallbackClassname, $this->subjectable);
    }
}

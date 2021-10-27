<?php
namespace TRegx\CleanRegex\Internal\Factory\Optional;

use TRegx\CleanRegex\Internal\ClassName;
use TRegx\CleanRegex\Internal\Message\NotMatchedMessage;

class ArgumentlessOptionalWorker implements OptionalWorker
{
    /** @var string */
    private $fallbackClassname;
    /** @var NotMatchedMessage */
    private $message;

    public function __construct(NotMatchedMessage $message, string $defaultExceptionClassname)
    {
        $this->fallbackClassname = $defaultExceptionClassname;
        $this->message = $message;
    }

    public function arguments(): array
    {
        return [];
    }

    public function throwable(?string $exceptionClassname): \Throwable
    {
        $className = new ClassName($exceptionClassname ?? $this->fallbackClassname);
        return $className->throwable($this->message, null);
    }
}

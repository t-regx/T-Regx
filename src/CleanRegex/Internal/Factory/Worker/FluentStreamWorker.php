<?php
namespace TRegx\CleanRegex\Internal\Factory\Worker;

use TRegx\CleanRegex\Exception\NoSuchElementFluentException;
use TRegx\CleanRegex\Internal\Exception\Messages\FirstFluentMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\FirstMatchFluentMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\Factory\Optional\ArgumentlessOptionalWorker;
use TRegx\CleanRegex\Internal\Factory\Optional\OptionalWorker;

class FluentStreamWorker implements StreamWorker
{
    /** @var NotMatchedMessage */
    private $noFirstMessage;
    /** @var NotMatchedMessage */
    private $unmatchedMessage;

    private function __construct(NotMatchedMessage $noFirstMessage, NotMatchedMessage $unmatchedMessage)
    {
        $this->noFirstMessage = $noFirstMessage;
        $this->unmatchedMessage = $unmatchedMessage;
    }

    public static function default(): self
    {
        return new self(new FirstFluentMessage(), new FirstFluentMessage());
    }

    public static function subject(FirstMatchFluentMessage $message): self
    {
        return new self(new FirstFluentMessage(), $message);
    }

    public function undecorateWorker(): StreamWorker
    {
        return $this;
    }

    public function noFirstOptionalWorker(): OptionalWorker
    {
        return new ArgumentlessOptionalWorker($this->noFirstMessage, NoSuchElementFluentException::class);
    }

    public function unmatchedOptionalWorker(): OptionalWorker
    {
        return new ArgumentlessOptionalWorker($this->unmatchedMessage, NoSuchElementFluentException::class);
    }
}

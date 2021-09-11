<?php
namespace TRegx\CleanRegex\Internal\Messages\Subject;

use TRegx\CleanRegex\Internal\Messages\NotMatchedMessage;

class NthMatchFluentMessage implements NotMatchedMessage
{
    /** @var int */
    private $nth;

    public function __construct(int $nth)
    {
        $this->nth = $nth;
    }

    public function getMessage(): string
    {
        return "Expected to get the $this->nth-nth element from fluent pattern, but the subject backing the feed was not matched";
    }
}

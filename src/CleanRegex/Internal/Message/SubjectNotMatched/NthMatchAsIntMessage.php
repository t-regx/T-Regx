<?php
namespace TRegx\CleanRegex\Internal\Message\SubjectNotMatched;

use TRegx\CleanRegex\Internal\Index;
use TRegx\CleanRegex\Internal\Message\Message;

class NthMatchAsIntMessage implements Message
{
    /** @var Index */
    private $index;

    public function __construct(Index $index)
    {
        $this->index = $index;
    }

    public function getMessage(): string
    {
        return "Expected to get the $this->index-nth match as integer, but subject was not matched";
    }
}

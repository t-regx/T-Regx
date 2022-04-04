<?php
namespace TRegx\CleanRegex\Internal\Message\SubjectNotMatched;

use TRegx\CleanRegex\Internal\Message\Message;

class NthMatchAsIntMessage implements Message
{
    /** @var int */
    private $nth;

    public function __construct(int $nth)
    {
        $this->nth = $nth;
    }

    public function getMessage(): string
    {
        return "Expected to get the $this->nth-nth match as integer, but subject was not matched";
    }
}

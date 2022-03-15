<?php
namespace TRegx\CleanRegex\Internal;

class Subject
{
    /** @var string */
    private $subject;

    public function __construct(string $subject)
    {
        $this->subject = $subject;
    }

    public function asString(): string
    {
        return $this->subject;
    }

    public function __toString(): string
    {
        return $this->subject;
    }
}

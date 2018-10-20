<?php
namespace TRegx\CleanRegex\Internal;

class SubjectableEx implements Subjectable
{
    /** @var string */
    private $subject;

    public function __construct(string $subject)
    {
        $this->subject = $subject;
    }

    public function getSubject(): string
    {
        throw new \Exception();
    }
}

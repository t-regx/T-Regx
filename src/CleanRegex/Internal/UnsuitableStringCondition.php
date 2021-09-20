<?php
namespace TRegx\CleanRegex\Internal;

class UnsuitableStringCondition implements Condition
{
    /** @var Chars */
    private $string;

    public function __construct(string $string)
    {
        $this->string = new Chars($string);
    }

    public function suitable(string $candidate): bool
    {
        return !$this->string->contains($candidate);
    }
}

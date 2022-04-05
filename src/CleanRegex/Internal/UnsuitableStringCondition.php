<?php
namespace TRegx\CleanRegex\Internal;

class UnsuitableStringCondition implements Condition
{
    /** @var string */
    private $string;

    public function __construct(string $string)
    {
        $this->string = $string;
    }

    public function suitable(string $candidate): bool
    {
        return \strPos($this->string, $candidate) === false;
    }
}

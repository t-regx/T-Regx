<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

class ConstantResultStrategy implements NonReplacedStrategy
{
    /** @var string */
    private $constant;

    public function __construct(string $constant)
    {
        $this->constant = $constant;
    }

    public function replacementResult(string $subject): ?string
    {
        return $this->constant;
    }
}

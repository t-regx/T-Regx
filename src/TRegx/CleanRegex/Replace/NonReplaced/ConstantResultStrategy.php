<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

class ConstantResultStrategy implements ReplaceSubstitute
{
    /** @var string */
    private $constant;

    public function __construct(string $constant)
    {
        $this->constant = $constant;
    }

    public function substitute(string $subject): ?string
    {
        return $this->constant;
    }
}

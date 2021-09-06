<?php
namespace TRegx\CleanRegex\Internal\Prepared\Orthography;

use TRegx\CleanRegex\Internal\Prepared\Condition\CompositeCondition;
use TRegx\CleanRegex\Internal\Prepared\Condition\Condition;
use TRegx\CleanRegex\Internal\Prepared\Condition\UnsuitableStringCondition;

class StandardOrthography implements Orthography
{
    /** @var string */
    private $input;
    /** @var string */
    private $flags;

    public function __construct(string $input, string $flags)
    {
        $this->input = $input;
        $this->flags = $flags;
    }

    public function spelling(Condition $condition): Spelling
    {
        return new StandardSpelling($this->input, $this->flags, new CompositeCondition([new UnsuitableStringCondition($this->input), $condition]));
    }
}

<?php
namespace TRegx\CleanRegex\Internal\Prepared\Orthography;

use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Condition\CompositeCondition;
use TRegx\CleanRegex\Internal\Prepared\Condition\Condition;
use TRegx\CleanRegex\Internal\Prepared\Condition\UnsuitableStringCondition;

class StandardOrthography implements Orthography
{
    /** @var string */
    private $input;
    /** @var Flags */
    private $flags;

    public function __construct(string $input, string $flags)
    {
        $this->input = $input;
        $this->flags = new Flags($flags);
    }

    public function spelling(Condition $condition): Spelling
    {
        return new StandardSpelling($this->input, $this->flags, new CompositeCondition([new UnsuitableStringCondition($this->input), $condition]));
    }
}

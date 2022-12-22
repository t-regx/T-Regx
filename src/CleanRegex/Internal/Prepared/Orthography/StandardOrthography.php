<?php
namespace TRegx\CleanRegex\Internal\Prepared\Orthography;

use TRegx\CleanRegex\Internal\Condition;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\CompositeCondition;
use TRegx\CleanRegex\Internal\UnsuitableStringCondition;

class StandardOrthography implements Orthography
{
    /** @var string */
    private $input;
    /** @var Flags */
    private $flags;
    /** @var Condition */
    private $inputCondition;

    public function __construct(string $input, Flags $flags)
    {
        $this->input = $input;
        $this->flags = $flags;
        $this->inputCondition = new UnsuitableStringCondition($input);
    }

    public function spelling(Condition $condition): Spelling
    {
        return new StandardSpelling($this->input, $this->flags, new CompositeCondition($this->inputCondition, $condition));
    }

    public function flags(): Flags
    {
        return $this->flags;
    }
}

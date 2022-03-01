<?php
namespace TRegx\CleanRegex\Internal\Prepared\Orthography;

use TRegx\CleanRegex\Internal\Candidates;
use TRegx\CleanRegex\Internal\Condition;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Flags;

class StandardSpelling implements Spelling
{
    /** @var string */
    public $input;
    /** @var Flags */
    private $flags;
    /** @var Candidates */
    private $delimiters;

    public function __construct(string $input, Flags $flags, Condition $condition)
    {
        $this->input = $input;
        $this->flags = $flags;
        $this->delimiters = new Candidates($condition);
    }

    public function delimiter(): Delimiter
    {
        return $this->delimiters->delimiter();
    }

    public function pattern(): string
    {
        return $this->input;
    }

    public function flags(): Flags
    {
        return $this->flags;
    }

    public function undevelopedInput(): string
    {
        return $this->input;
    }
}

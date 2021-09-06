<?php
namespace TRegx\CleanRegex\Internal\Prepared\Orthography;

use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Candidates;
use TRegx\CleanRegex\Internal\Prepared\Condition\Condition;

class StandardSpelling implements Spelling
{
    /** @var string */
    public $input;
    /** @var string */
    private $flags;
    /** @var Candidates */
    private $delimiters;

    public function __construct(string $input, string $flags, Condition $condition)
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
        return new Flags($this->flags);
    }

    public function undevelopedInput(): string
    {
        return $this->input;
    }
}

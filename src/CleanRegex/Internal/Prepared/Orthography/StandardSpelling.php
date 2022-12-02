<?php
namespace TRegx\CleanRegex\Internal\Prepared\Orthography;

use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Internal\Candidates;
use TRegx\CleanRegex\Internal\Condition;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Parser\SubpatternFlags;

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
        if (\strPos($this->input, "\0") === false) {
            return $this->input;
        }
        throw new PatternMalformedPatternException('Pattern may not contain null-byte');
    }

    public function flags(): Flags
    {
        return $this->flags;
    }

    public function subpatternFlags(): SubpatternFlags
    {
        return SubpatternFlags::from($this->flags);
    }
}

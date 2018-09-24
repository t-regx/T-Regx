<?php
namespace TRegx\CleanRegex\Internal\GroupLimit;

use TRegx\CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\SafeRegex\preg;
use function array_key_exists;

class GroupLimitAll
{
    /** @var Pattern */
    private $pattern;
    /** @var string */
    private $subject;
    /** @var string|int */
    private $nameOrIndex;

    public function __construct(Pattern $pattern, string $subject, $nameOrIndex)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
        $this->nameOrIndex = $nameOrIndex;
    }

    public function getAllForGroup(): array
    {
        $matches = [];
        preg::match_all($this->pattern->pattern, $this->subject, $matches, $this->pregMatchFlags());
        if (array_key_exists($this->nameOrIndex, $matches)) {
            return $matches[$this->nameOrIndex];
        }
        throw new NonexistentGroupException($this->nameOrIndex);
    }

    private function pregMatchFlags(): int
    {
        if (defined('PREG_UNMATCHED_AS_NULL')) {
            return PREG_UNMATCHED_AS_NULL;
        }
        return 0;
    }
}

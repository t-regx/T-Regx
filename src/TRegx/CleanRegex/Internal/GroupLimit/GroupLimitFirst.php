<?php
namespace TRegx\CleanRegex\Internal\GroupLimit;

use TRegx\CleanRegex\Exception\CleanRegex\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\SafeRegex\preg;
use function array_key_exists;

class GroupLimitFirst
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

    public function getFirstForGroup(): string
    {
        $matches = [];
        $count = preg::match($this->pattern->pattern, $this->subject, $matches, $this->pregMatchFlags());
        if ($count === 0) {
            throw SubjectNotMatchedException::forFirst($this->subject);
        }
        if (array_key_exists($this->nameOrIndex, $matches)) {
            list($value, $offset) = $matches[$this->nameOrIndex];
            if ($offset === -1) {
                throw GroupNotMatchedException::forFirst($this->subject, $this->nameOrIndex);
            }
            return $value;
        }
        throw new NonexistentGroupException($this->nameOrIndex);
    }

    private function pregMatchFlags(): int
    {
        if (defined('PREG_UNMATCHED_AS_NULL')) {
            return PREG_UNMATCHED_AS_NULL;
        }
        return PREG_OFFSET_CAPTURE;
    }
}

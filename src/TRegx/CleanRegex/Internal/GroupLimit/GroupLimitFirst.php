<?php
namespace TRegx\CleanRegex\Internal\GroupLimit;

use TRegx\CleanRegex\Exception\CleanRegex\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\CleanRegex\InternalCleanRegexException;
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
        return $this->getGroupOrThrow($matches);
    }

    private function pregMatchFlags(): int
    {
        if (defined('PREG_UNMATCHED_AS_NULL')) {
            return PREG_UNMATCHED_AS_NULL;
        }
        return PREG_OFFSET_CAPTURE;
    }

    private function getGroupOrThrow(array $matches): string
    {
        if (array_key_exists($this->nameOrIndex, $matches)) {
            return $this->tryGetGroup($matches[$this->nameOrIndex]);
        }
        throw new NonexistentGroupException($this->nameOrIndex);
    }

    /**
     * @param array|string|null $match
     * @return string
     * @throws GroupNotMatchedException
     */
    private function tryGetGroup($match): string
    {
        $group = $this->getGroupFromMatch($match);
        if ($group === null) {
            throw GroupNotMatchedException::forFirst($this->subject, $this->nameOrIndex);
        }
        return $group;
    }

    /**
     * @param array|string|null $match
     * @return string|null
     */
    private function getGroupFromMatch($match): ?string
    {
        if ($match === null) {
            return null;
        }
        if (is_string($match)) {
            return $match;
        }
        if (is_array($match)) {
            list($value, $offset) = $match;
            if ($offset === -1) {
                return null;
            }
            return $value;
        }
        throw new InternalCleanRegexException();
    }
}

<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Feed;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;

class MatchedString
{
    /** @var ShiftString */
    private $shiftString;
    /** @var string */
    private $pattern;
    /** @var int */
    private $groups;

    public function __construct(ShiftString $shiftString, string $pattern, int $groups)
    {
        $this->shiftString = $shiftString;
        $this->pattern = $pattern;
        $this->groups = $groups;
    }

    public function matched(): bool
    {
        return \preg_match($this->pattern, $this->shiftString->content()) === 1;
    }

    public function consume(): array
    {
        [$full, $groups] = $this->match();
        $this->shiftString->shift($full);
        return $this->matchedGroups($groups);
    }

    private function match(): array
    {
        if (\preg_match($this->pattern, $this->shiftString->content(), $matches, \PREG_OFFSET_CAPTURE) === 1) {
            if ($matches[0][1] === 0) {
                [[$full]] = $matches;
                return [$full, \array_slice($matches, 1)];
            }
        }
        // @codeCoverageIgnoreStart
        throw new InternalCleanRegexException();
        // @codeCoverageIgnoreEnd
    }

    private function matchedGroups(array $groups): array
    {
        $result = \array_fill(0, $this->groups, null);
        foreach ($groups as $index => [$text, $offset]) {
            if ($offset > -1) {
                $result[$index] = $text;
            }
        }
        return $result;
    }
}

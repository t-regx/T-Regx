<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Feed;

class MatchedString
{
    /** @var Feed */
    private $feed;
    /** @var int */
    private $groups;
    /** @var (string|int)[][] */
    private $matches;

    public function __construct(Feed $feed, string $pattern, int $groups)
    {
        $this->feed = $feed;
        $this->groups = $groups;
        \preg_match($pattern, $feed->content(), $this->matches, \PREG_OFFSET_CAPTURE);
    }

    public function matched(): bool
    {
        return !empty($this->matches);
    }

    public function consume(): array
    {
        $this->feed->commit($this->matches[0][0]);
        return $this->matchedGroups();
    }

    private function matchedGroups(): array
    {
        $result = \array_fill(0, $this->groups, null);
        foreach ($this->matches as $index => [$text, $offset]) {
            if ($index === 0) {
                continue;
            }
            if ($offset > -1) {
                $result[$index - 1] = $text;
            }
        }
        return $result;
    }
}

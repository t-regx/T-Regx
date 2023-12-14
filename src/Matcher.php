<?php
namespace Regex;

use Regex\Internal\GroupKeys;
use Regex\Internal\Pcre;

final class Matcher implements \Countable
{
    private array $matches;
    private string $subject;
    private GroupKeys $groupKeys;

    public function __construct(Pcre $pcre, string $subject, GroupKeys $groupKeys)
    {
        $this->matches = $pcre->fullMatch($subject);
        $this->subject = $subject;
        $this->groupKeys = $groupKeys;
    }

    public function test(): bool
    {
        return !empty($this->matches);
    }

    public function first(): Detail
    {
        if (empty($this->matches)) {
            throw new NoMatchException();
        }
        return new Detail($this->matches[0], $this->subject, $this->groupKeys, 0);
    }

    public function firstOrNull(): ?Detail
    {
        if (empty($this->matches)) {
            return null;
        }
        return new Detail($this->matches[0], $this->subject, $this->groupKeys, 0);
    }

    public function count(): int
    {
        return \count($this->matches);
    }

    /**
     * @return Detail[]
     */
    public function all(): array
    {
        $details = [];
        foreach ($this->matches as $index => $match) {
            $details[] = new Detail($match, $this->subject, $this->groupKeys, $index);
        }
        return $details;
    }
}

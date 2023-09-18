<?php
namespace Regex;

use Regex\Internal\GroupKeys;
use Regex\Internal\Pcre;

final class Matcher
{
    private array $match;
    private string $subject;
    private GroupKeys $groupKeys;

    public function __construct(Pcre $pcre, string $subject, GroupKeys $groupKeys)
    {
        $this->match = $pcre->matchFirst($subject);
        $this->subject = $subject;
        $this->groupKeys = $groupKeys;
    }

    public function test(): bool
    {
        return !empty($this->match[0]);
    }

    public function first(): Detail
    {
        if (empty($this->match[0])) {
            throw new NoMatchException();
        }
        return new Detail($this->match, $this->subject, $this->groupKeys, 0);
    }

    public function firstOrNull(): ?Detail
    {
        if (empty($this->match[0])) {
            return null;
        }
        return new Detail($this->match, $this->subject, $this->groupKeys, 0);
    }
}

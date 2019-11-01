<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Match\Details\Match;

class UserData
{
    /** @var mixed[] */
    private $values = [];

    public function set(Match $match, $value): void
    {
        $this->values[$this->getKey($match)] = $value;
    }

    public function get(Match $match)
    {
        $key = $this->getKey($match);
        return $this->values[$key] ?? null;
    }

    private function getKey(Match $match): int
    {
        return $match->byteOffset();
    }
}

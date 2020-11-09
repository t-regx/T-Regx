<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Match\Details\Detail;

class UserData
{
    /** @var mixed[] */
    private $values = [];

    public function set(Detail $match, $value): void
    {
        $this->values[$this->getKey($match)] = $value;
    }

    public function get(Detail $match)
    {
        $key = $this->getKey($match);
        return $this->values[$key] ?? null;
    }

    private function getKey(Detail $match): int
    {
        return $match->byteOffset();
    }
}

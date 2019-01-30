<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Match\Details\Match;
use function array_key_exists;

class UserData
{
    /** @var UserDataObject[] */
    private $values = [];

    public function forMatch(Match $match): UserDataObject
    {
        return $this->getOrCreate($this->getKey($match));
    }

    private function getOrCreate(int $key): UserDataObject
    {
        if (!array_key_exists($key, $this->values)) {
            $this->values[$key] = new UserDataObject();
        }
        return $this->values[$key];
    }

    private function getKey(Match $match): int
    {
        return $match->byteOffset();
    }
}

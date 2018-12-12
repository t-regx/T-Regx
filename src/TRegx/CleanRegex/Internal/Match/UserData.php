<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Match\Details\Match;

class UserData
{
    /** @var UserDataObject[] */
    private $values = [];

    public function get(Match $match): UserDataObject
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

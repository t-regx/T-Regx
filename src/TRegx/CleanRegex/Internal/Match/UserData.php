<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Match\Details\Detail;

class UserData
{
    /** @var mixed[] */
    private $values = [];

    public function set(Detail $detail, $value): void
    {
        $this->values[$this->getKey($detail)] = $value;
    }

    public function get(Detail $detail)
    {
        $key = $this->getKey($detail);
        return $this->values[$key] ?? null;
    }

    private function getKey(Detail $detail): int
    {
        return $detail->byteOffset();
    }
}

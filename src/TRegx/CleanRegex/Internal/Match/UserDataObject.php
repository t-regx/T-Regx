<?php
namespace TRegx\CleanRegex\Internal\Match;

class UserDataObject
{
    /** @var mixed|null */
    private $value;

    public function get()
    {
        return $this->value;
    }

    public function set($value): void
    {
        $this->value = $value;
    }
}

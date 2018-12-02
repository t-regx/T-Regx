<?php
namespace TRegx\CleanRegex\Internal\Match;

class UserDataObject
{
    /** @var mixed|null */
    private $value = null;

    public function get()
    {
        return $this->value;
    }

    public function set($value): void
    {
        $this->value = $value;
    }
}

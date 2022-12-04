<?php
namespace TRegx\CleanRegex\Internal\GroupKey;

use TRegx\CleanRegex\Internal\InvalidArgument;

class GroupName extends GroupKey
{
    /** @var string */
    private $name;

    public function __construct(string $name)
    {
        if (\preg_match('/^[_a-zA-Z][a-zA-Z0-9_]{0,31}$/DS', $name) === 0) {
            throw InvalidArgument::typeGiven('Group name must be an alphanumeric string, not starting with a digit', new GroupNameType($name));
        }
        $this->name = $name;
    }

    public function nameOrIndex(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return "'$this->name'";
    }
}

<?php
namespace TRegx\CleanRegex\Internal\GroupKey;

use TRegx\CleanRegex\Internal\InvalidArgument;

class GroupName extends GroupKey
{
    /** @var string */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function nameOrIndex(): string
    {
        if ($this->isGroupNameValid()) {
            return $this->name;
        }
        throw InvalidArgument::typeGiven('Group name must be an alphanumeric string, not starting with a digit', new GroupNameType($this->name));
    }

    private function isGroupNameValid(): bool
    {
        return \preg_match('/^[_a-zA-Z][a-zA-Z0-9_]{0,31}$/D', $this->name) > 0;
    }

    public function full(): bool
    {
        return false;
    }

    public function __toString(): string
    {
        return "'$this->name'";
    }
}

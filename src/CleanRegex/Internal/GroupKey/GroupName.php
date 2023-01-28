<?php
namespace TRegx\CleanRegex\Internal\GroupKey;

use TRegx\CleanRegex\Internal\InvalidArgument;

class GroupName extends GroupKey
{
    /** @var string */
    private $name;

    public function __construct(string $name)
    {
        if (\preg_match('/^[_\p{L}][_\p{L}\p{Nd}]{0,31}$/DSu', $name)) {
            $this->name = $name;
        } else {
            throw InvalidArgument::typeGiven('Group name must be an alphanumeric string, not starting with a digit', new GroupNameType($name));
        }
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

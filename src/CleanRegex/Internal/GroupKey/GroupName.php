<?php
namespace TRegx\CleanRegex\Internal\GroupKey;

use TRegx\CleanRegex\Internal\InvalidArgument;
use TRegx\Pcre;

class GroupName extends GroupKey
{
    /** @var string */
    private $name;

    public function __construct(string $name)
    {
        if (Pcre::pcre2()) {
            $pattern = '/^[_\p{L}][_\p{L}\p{Nd}]{0,31}$/DSu';
        } else {
            $pattern = '/^[_a-zA-Z][a-zA-Z0-9_]{0,31}$/DS';
        }
        if (\preg_match($pattern, $name) === 1) {
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

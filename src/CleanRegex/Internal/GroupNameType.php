<?php
namespace TRegx\CleanRegex\Internal;

class GroupNameType implements Type
{
    /** @var InvisibleCharacters */
    private $string;

    public function __construct(string $string)
    {
        $this->string = new InvisibleCharacters($string);
    }

    public function __toString(): string
    {
        return "'$this->string'";
    }
}

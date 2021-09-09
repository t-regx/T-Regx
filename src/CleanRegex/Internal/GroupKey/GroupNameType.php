<?php
namespace TRegx\CleanRegex\Internal\GroupKey;

use TRegx\CleanRegex\Internal\InvisibleCharacters;
use TRegx\CleanRegex\Internal\Type\Type;

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

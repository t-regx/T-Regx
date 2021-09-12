<?php
namespace TRegx\CleanRegex\Internal\GroupKey;

use TRegx\CleanRegex\Internal\Type\Type;
use TRegx\CleanRegex\Internal\VisibleCharacters;

class GroupNameType implements Type
{
    /** @var VisibleCharacters */
    private $string;

    public function __construct(string $string)
    {
        $this->string = new VisibleCharacters($string);
    }

    public function __toString(): string
    {
        return "'$this->string'";
    }
}

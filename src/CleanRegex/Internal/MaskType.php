<?php
namespace TRegx\CleanRegex\Internal;

class MaskType implements Type
{
    /** @var string[] */
    private $keywords;

    public function __construct(array $keywords)
    {
        $this->keywords = $keywords;
    }

    public function __toString(): string
    {
        $count = \count($this->keywords);
        return "mask ($count)";
    }
}

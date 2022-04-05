<?php
namespace TRegx\CleanRegex\Internal;

class Nested
{
    /** @var array */
    private $nestedValues;

    public function __construct(array $nested)
    {
        $this->nestedValues = $nested;
    }

    public function asArray(): array
    {
        return $this->nestedValues;
    }
}

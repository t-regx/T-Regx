<?php
namespace Regex\Internal;

class ReplaceFunction
{
    /** @var callable */
    private $replacer;

    public function __construct(callable $replacer)
    {
        $this->replacer = $replacer;
    }

    public function apply(array $match): string
    {
        $result = ($this->replacer)($match[0]);
        if (\is_string($result)) {
            return $result;
        }
        $type = new Type($result);
        throw new \UnexpectedValueException("Replacement must be of type string, given: $type.");
    }
}

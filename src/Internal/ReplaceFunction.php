<?php
namespace Regex\Internal;

use Regex\Detail;

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
        [[$text, $offset]] = $match;
        return $this->replace(new Detail($text, $offset));
    }

    private function replace(Detail $detail): string
    {
        $result = ($this->replacer)($detail);
        if (\is_string($result)) {
            return $result;
        }
        $type = new Type($result);
        throw new \UnexpectedValueException("Replacement must be of type string, given: $type.");
    }
}

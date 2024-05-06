<?php
namespace Regex\Internal;

use Regex\Detail;

class ReplaceFunction
{
    /** @var callable */
    private $replacer;
    private string $subject;
    private GroupKeys $groupKeys;

    public function __construct(callable $replacer, string $subject, GroupKeys $groupKeys)
    {
        $this->replacer = $replacer;
        $this->subject = $subject;
        $this->groupKeys = $groupKeys;
    }

    public function apply(array $match): string
    {
        [[$text, $offset]] = $match;
        return $this->replace(new Detail($text, $offset, $this->subject, $this->groupKeys));
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

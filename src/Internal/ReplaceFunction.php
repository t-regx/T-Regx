<?php
namespace Regex\Internal;

use Regex\Detail;

class ReplaceFunction implements Replacer
{
    /** @var callable */
    private $replacer;
    private string $subject;
    private GroupKeys $groupKeys;
    private int $sequence = 0;

    public function __construct(callable $replacer, string $subject, GroupKeys $groupKeys)
    {
        $this->replacer = $replacer;
        $this->subject = $subject;
        $this->groupKeys = $groupKeys;
    }

    public function replace(array $match): string
    {
        return $this->apply(new Detail($match, $this->subject, $this->groupKeys, $this->sequence++));
    }

    private function apply(Detail $detail): string
    {
        $result = ($this->replacer)($detail);
        if (\is_string($result)) {
            return $result;
        }
        $type = new Type($result);
        throw new \UnexpectedValueException("Replacement must be of type string, given: $type.");
    }
}

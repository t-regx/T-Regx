<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Feed;

class StringConditions
{
    /** @var string[] */
    private $strings = [];

    public function add(StringCondition $condition): void
    {
        $this->strings[] = $condition->asString();
        $condition->commit();
    }

    public function asString(): string
    {
        return \join($this->strings);
    }
}

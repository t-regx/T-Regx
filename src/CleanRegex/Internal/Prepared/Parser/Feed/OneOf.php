<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Feed;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;

class OneOf implements StringCondition
{
    /** @var Feed */
    private $feed;
    /** @var array */
    private $values;

    public function __construct(Feed $feed, array $values)
    {
        $this->feed = $feed;
        $this->values = $values;
    }

    public function consumable(): bool
    {
        foreach ($this->values as $value) {
            if ($this->feed->startsWith($value)) {
                return true;
            }
        }
        return false;
    }

    public function asString(): string
    {
        foreach ($this->values as $value) {
            if ($this->feed->startsWith($value)) {
                return $value;
            }
        }
        // @codeCoverageIgnoreStart
        throw new InternalCleanRegexException();
        // @codeCoverageIgnoreEnd
    }

    public function commit(): void
    {
        $this->feed->commit($this->asString());
    }
}

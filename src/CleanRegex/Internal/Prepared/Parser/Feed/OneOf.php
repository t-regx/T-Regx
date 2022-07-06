<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Feed;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;

class OneOf implements StringCondition
{
    /** @var ShiftString */
    private $shiftString;
    /** @var array */
    private $values;

    public function __construct(ShiftString $shiftString, array $values)
    {
        $this->shiftString = $shiftString;
        $this->values = $values;
    }

    public function consumable(): bool
    {
        foreach ($this->values as $value) {
            if ($this->shiftString->startsWith($value)) {
                return true;
            }
        }
        return false;
    }

    public function asString(): string
    {
        foreach ($this->values as $value) {
            if ($this->shiftString->startsWith($value)) {
                return $value;
            }
        }
        // @codeCoverageIgnoreStart
        throw new InternalCleanRegexException();
        // @codeCoverageIgnoreEnd
    }

    public function commit(): void
    {
        $this->shiftString->shift($this->asString());
    }
}

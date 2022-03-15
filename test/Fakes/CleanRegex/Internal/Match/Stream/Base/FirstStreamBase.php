<?php
namespace Test\Fakes\CleanRegex\Internal\Match\Stream\Base;

use AssertionError;
use Test\Fakes\CleanRegex\Internal\Match\Base\ThrowApiBase;
use TRegx\CleanRegex\Internal\Match\Stream\Base\StreamBase;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;

class FirstStreamBase extends StreamBase
{
    /** @var int|null */
    private $index;
    /** @var RawMatchOffset */
    private $match;

    public function __construct(?int $index, RawMatchOffset $match)
    {
        parent::__construct(new ThrowApiBase());
        $this->index = $index;
        $this->match = $match;
    }

    public static function text(string $value): self
    {
        return new self(null, new RawMatchOffset([[$value, 0]]));
    }

    public static function entry(int $index, string $value, int $offset = null): self
    {
        return new self($index, new RawMatchOffset([[$value, $offset ?? 0]]));
    }

    public static function dummy(): self
    {
        return new self(-99, new RawMatchOffset([['', -99]]));
    }

    public function first(): RawMatchOffset
    {
        return $this->match;
    }

    public function firstKey(): int
    {
        if ($this->index === null) {
            throw new AssertionError("Failed to assert that StreamBase.firstKey() wasn't used");
        }
        return $this->index;
    }
}

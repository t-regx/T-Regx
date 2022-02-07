<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Exception\InvalidIntegerTypeException;
use TRegx\CleanRegex\Internal\Match\Stream\Number\IdentityNumber;
use TRegx\CleanRegex\Internal\Match\Stream\Number\IntableNumber;
use TRegx\CleanRegex\Internal\Match\Stream\Number\Number;
use TRegx\CleanRegex\Internal\Match\Stream\Number\StreamNumber;
use TRegx\CleanRegex\Internal\Numeral\Base;
use TRegx\CleanRegex\Internal\Type\ValueType;
use TRegx\CleanRegex\Match\Details\Intable;

class IntegerStream implements Upstream
{
    use PreservesKey;

    /** @var Upstream */
    private $upstream;
    /** @var Base */
    private $base;

    public function __construct(Upstream $upstream, Base $base)
    {
        $this->upstream = $upstream;
        $this->base = $base;
    }

    public function all(): array
    {
        return \array_map([$this, 'parsedNumber'], $this->upstream->all());
    }

    public function first(): int
    {
        return $this->parsedNumber($this->upstream->first());
    }

    private function parsedNumber($value): int
    {
        return $this->number($value)->toInt();
    }

    private function number($value): Number
    {
        if (\is_int($value)) {
            return new IdentityNumber($value);
        }
        if ($value instanceof Intable) {
            return new IntableNumber($value, $this->base);
        }
        if (\is_string($value)) {
            return new StreamNumber($value, $this->base);
        }
        throw InvalidIntegerTypeException::forInvalidType(new ValueType($value));
    }
}

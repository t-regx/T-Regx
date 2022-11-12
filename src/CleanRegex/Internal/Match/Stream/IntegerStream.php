<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Exception\InvalidIntegerTypeException;
use TRegx\CleanRegex\Internal\Match\Intable;
use TRegx\CleanRegex\Internal\Match\Numeral\IntegerBase;
use TRegx\CleanRegex\Internal\Match\Numeral\StreamExceptions;
use TRegx\CleanRegex\Internal\Numeral\Base;
use TRegx\CleanRegex\Internal\Type\ValueType;

class IntegerStream implements Upstream
{
    /** @var Upstream */
    private $upstream;
    /** @var IntegerBase */
    private $base;

    public function __construct(Upstream $upstream, Base $base)
    {
        $this->upstream = $upstream;
        $this->base = new IntegerBase($base, new StreamExceptions());
    }

    public function all(): array
    {
        return \array_map([$this, 'number'], $this->upstream->all());
    }

    public function first(): array
    {
        [$key, $value] = $this->upstream->first();
        return [$key, $this->number($value)];
    }

    private function number($value): int
    {
        if (\is_int($value)) {
            return $value;
        }
        if ($value instanceof Intable) {
            return $value->toInt($this->base->base());
        }
        if (\is_string($value)) {
            return $this->base->integer($value);
        }
        $type = new ValueType($value);
        throw new InvalidIntegerTypeException("Failed to parse value as integer. Expected integer|string, but $type given");
    }
}

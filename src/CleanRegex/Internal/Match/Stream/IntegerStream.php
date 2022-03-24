<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Exception\InvalidIntegerTypeException;
use TRegx\CleanRegex\Internal\Match\Numeral\IntegerBase;
use TRegx\CleanRegex\Internal\Match\Numeral\StreamExceptions;
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
    /** @var IntegerBase */
    private $integerBase;

    public function __construct(Upstream $upstream, Base $base)
    {
        $this->upstream = $upstream;
        $this->base = $base;
        $this->integerBase = new IntegerBase($this->base, new StreamExceptions());
    }

    public function all(): array
    {
        return \array_map([$this, 'number'], $this->upstream->all());
    }

    public function first(): int
    {
        return $this->number($this->upstream->first());
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
            return $this->integerBase->integer($value);
        }
        throw InvalidIntegerTypeException::forInvalidType(new ValueType($value));
    }
}

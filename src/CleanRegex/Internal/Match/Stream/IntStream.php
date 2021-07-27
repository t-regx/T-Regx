<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Exception\FluentMatchPatternException;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Internal\Integer;
use TRegx\CleanRegex\Internal\ValueType;
use TRegx\CleanRegex\Match\Details\Intable;

class IntStream implements Stream
{
    use PreservesKey;

    /** @var Stream */
    private $stream;

    public function __construct(Stream $stream)
    {
        $this->stream = $stream;
    }

    public function all(): array
    {
        return \array_map([$this, 'parse'], $this->stream->all());
    }

    public function first(): int
    {
        return self::parse($this->stream->first());
    }

    private static function parse($value): int
    {
        if (\is_int($value)) {
            return $value;
        }
        if ($value instanceof Intable) {
            return $value->toInt();
        }
        if (!\is_string($value)) {
            throw FluentMatchPatternException::forInvalidInteger(new ValueType($value));
        }
        if (Integer::isValid($value)) {
            return (int)$value;
        }
        throw IntegerFormatException::forFluent($value);
    }
}

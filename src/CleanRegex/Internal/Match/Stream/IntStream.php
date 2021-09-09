<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Exception\FluentMatchPatternException;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\IntegerOverflowException;
use TRegx\CleanRegex\Internal\Number\Base;
use TRegx\CleanRegex\Internal\Number\NumberFormatException;
use TRegx\CleanRegex\Internal\Number\NumberOverflowException;
use TRegx\CleanRegex\Internal\Number\StringNumber;
use TRegx\CleanRegex\Internal\Type\ValueType;
use TRegx\CleanRegex\Match\Details\Intable;

class IntStream implements Stream
{
    use PreservesKey;

    /** @var Stream */
    private $stream;
    /** @var Base */
    private $base;

    public function __construct(Stream $stream, Base $base)
    {
        $this->stream = $stream;
        $this->base = $base;
    }

    public function all(): array
    {
        return \array_map([$this, 'parse'], $this->stream->all());
    }

    public function first(): int
    {
        return $this->parse($this->stream->first());
    }

    private function parse($value): int
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
        $number = new StringNumber($value);
        try {
            return $number->asInt($this->base);
        } catch (NumberOverflowException $exception) {
            throw IntegerOverflowException::forFluent($value, $this->base);
        } catch (NumberFormatException $exception) {
            throw IntegerFormatException::forFluent($value, $this->base);
        }
    }
}

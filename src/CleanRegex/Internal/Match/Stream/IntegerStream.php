<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\IntegerOverflowException;
use TRegx\CleanRegex\Exception\InvalidIntegerTypeException;
use TRegx\CleanRegex\Internal\Numeral\Base;
use TRegx\CleanRegex\Internal\Numeral\NumeralFormatException;
use TRegx\CleanRegex\Internal\Numeral\NumeralOverflowException;
use TRegx\CleanRegex\Internal\Numeral\StringNumeral;
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
        return \array_map([$this, 'parse'], $this->upstream->all());
    }

    public function first(): int
    {
        return $this->parse($this->upstream->first());
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
            throw InvalidIntegerTypeException::forInvalidType(new ValueType($value));
        }
        $number = new StringNumeral($value);
        try {
            return $number->asInt($this->base);
        } catch (NumeralOverflowException $exception) {
            throw IntegerOverflowException::forStream($value, $this->base);
        } catch (NumeralFormatException $exception) {
            throw IntegerFormatException::forStream($value, $this->base);
        }
    }
}

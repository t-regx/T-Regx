<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\IntegerOverflowException;
use TRegx\CleanRegex\Internal\Number\Base;
use TRegx\CleanRegex\Internal\Number\NumberFormatException;
use TRegx\CleanRegex\Internal\Number\NumberOverflowException;
use TRegx\CleanRegex\Internal\Number\StringNumber;

class MatchIntStream implements Stream
{
    use ListStream;

    /** @var StreamBase */
    private $stream;

    public function __construct(StreamBase $stream)
    {
        $this->stream = $stream;
    }

    protected function entries(): array
    {
        return \array_map([$this, 'parseInteger'], $this->stream->all()->getTexts());
    }

    protected function firstValue(): int
    {
        return $this->parseInteger($this->stream->first()->getText());
    }

    private function parseInteger(string $text): int
    {
        $number = new StringNumber($text);
        try {
            return $number->asInt(new Base(10));
        } catch (NumberFormatException $exception) {
            throw IntegerFormatException::forMatch($text);
        } catch (NumberOverflowException $exception) {
            throw IntegerOverflowException::forMatch($text);
        }
    }
}

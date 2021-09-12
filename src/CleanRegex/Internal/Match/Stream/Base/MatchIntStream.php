<?php
namespace TRegx\CleanRegex\Internal\Match\Stream\Base;

use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\IntegerOverflowException;
use TRegx\CleanRegex\Internal\Match\Stream\ListStream;
use TRegx\CleanRegex\Internal\Match\Stream\Upstream;
use TRegx\CleanRegex\Internal\Number\Base;
use TRegx\CleanRegex\Internal\Number\NumberFormatException;
use TRegx\CleanRegex\Internal\Number\NumberOverflowException;
use TRegx\CleanRegex\Internal\Number\StringNumber;

class MatchIntStream implements Upstream
{
    use ListStream;

    /** @var StreamBase */
    private $stream;
    /** @var Base */
    private $base;

    public function __construct(StreamBase $stream, Base $base)
    {
        $this->stream = $stream;
        $this->base = $base;
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
            return $number->asInt($this->base);
        } catch (NumberFormatException $exception) {
            throw IntegerFormatException::forMatch($text, $this->base);
        } catch (NumberOverflowException $exception) {
            throw IntegerOverflowException::forMatch($text, $this->base);
        }
    }
}

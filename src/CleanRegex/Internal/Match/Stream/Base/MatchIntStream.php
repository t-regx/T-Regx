<?php
namespace TRegx\CleanRegex\Internal\Match\Stream\Base;

use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\IntegerOverflowException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Match\Stream\ListStream;
use TRegx\CleanRegex\Internal\Match\Stream\StramRejectedException;
use TRegx\CleanRegex\Internal\Match\Stream\Upstream;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\FirstMatchAsIntMessage;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Numeral\Base;
use TRegx\CleanRegex\Internal\Numeral\NumeralFormatException;
use TRegx\CleanRegex\Internal\Numeral\NumeralOverflowException;
use TRegx\CleanRegex\Internal\Numeral\StringNumeral;
use TRegx\CleanRegex\Internal\Subject;

class MatchIntStream implements Upstream
{
    use ListStream;

    /** @var StreamBase */
    private $stream;
    /** @var Base */
    private $base;
    /** @var Subject */
    private $subject;

    public function __construct(StreamBase $stream, Base $base, Subject $subject)
    {
        $this->stream = $stream;
        $this->base = $base;
        $this->subject = $subject;
    }

    protected function entries(): array
    {
        return \array_map([$this, 'parseInteger'], $this->stream->all()->getTexts());
    }

    protected function firstValue(): int
    {
        return $this->parseInteger($this->firstMatch()->getText());
    }

    private function parseInteger(string $text): int
    {
        $number = new StringNumeral($text);
        try {
            return $number->asInt($this->base);
        } catch (NumeralFormatException $exception) {
            throw IntegerFormatException::forMatch($text, $this->base);
        } catch (NumeralOverflowException $exception) {
            throw IntegerOverflowException::forMatch($text, $this->base);
        }
    }

    private function firstMatch(): RawMatchOffset
    {
        try {
            return $this->stream->first();
        } catch (UnmatchedStreamException $exception) {
            throw new StramRejectedException($this->subject, SubjectNotMatchedException::class, new FirstMatchAsIntMessage());
        }
    }
}

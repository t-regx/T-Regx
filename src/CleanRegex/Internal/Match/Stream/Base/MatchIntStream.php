<?php
namespace TRegx\CleanRegex\Internal\Match\Stream\Base;

use TRegx\CleanRegex\Internal\Match\Numeral\IntegerBase;
use TRegx\CleanRegex\Internal\Match\Numeral\MatchExceptions;
use TRegx\CleanRegex\Internal\Match\Stream\SubjectStreamRejectedException;
use TRegx\CleanRegex\Internal\Match\Stream\Upstream;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\FirstMatchAsIntMessage;
use TRegx\CleanRegex\Internal\Numeral\Base;
use TRegx\CleanRegex\Internal\Subject;

class MatchIntStream implements Upstream
{
    use ListStream;

    /** @var StreamBase */
    private $stream;
    /** @var IntegerBase */
    private $base;
    /** @var Subject */
    private $subject;

    public function __construct(StreamBase $stream, Base $base, Subject $subject)
    {
        $this->stream = $stream;
        $this->base = new IntegerBase($base, new MatchExceptions());
        $this->subject = $subject;
    }

    protected function entries(): array
    {
        return \array_map([$this->base, 'integer'], $this->stream->all()->getTexts());
    }

    protected function firstValue(): int
    {
        try {
            return $this->base->integer($this->stream->first()->getText());
        } catch (UnmatchedStreamException $exception) {
            throw new SubjectStreamRejectedException(new FirstMatchAsIntMessage(), $this->subject);
        }
    }
}

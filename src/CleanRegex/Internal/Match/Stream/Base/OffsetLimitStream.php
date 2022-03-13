<?php
namespace TRegx\CleanRegex\Internal\Match\Stream\Base;

use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Stream\ListStream;
use TRegx\CleanRegex\Internal\Match\Stream\StreamRejectedException;
use TRegx\CleanRegex\Internal\Match\Stream\Upstream;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\FirstMatchOffsetMessage;
use TRegx\CleanRegex\Internal\Subject;

class OffsetLimitStream implements Upstream
{
    use ListStream;

    /** @var Base */
    private $base;
    /** @var Subject */
    private $subject;

    public function __construct(Base $base, Subject $subject)
    {
        $this->base = $base;
        $this->subject = $subject;
    }

    protected function entries(): array
    {
        $matches = $this->base->matchAllOffsets();
        if ($matches->matched()) {
            return $matches->getLimitedGroupOffsets(0, -1);
        }
        throw new UnmatchedStreamException();
    }

    protected function firstValue(): int
    {
        $match = $this->base->matchOffset();
        if ($match->matched()) {
            return $match->byteOffset();
        }
        throw new StreamRejectedException($this->subject, SubjectNotMatchedException::class, new FirstMatchOffsetMessage());
    }
}

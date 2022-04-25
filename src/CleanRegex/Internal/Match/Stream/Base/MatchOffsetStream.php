<?php
namespace TRegx\CleanRegex\Internal\Match\Stream\Base;

use TRegx\CleanRegex\Internal\Match\Stream\SubjectStreamRejectedException;
use TRegx\CleanRegex\Internal\Match\Stream\Upstream;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\FirstMatchOffsetMessage;
use TRegx\CleanRegex\Internal\Offset\ByteOffset;
use TRegx\CleanRegex\Internal\Pcre\Legacy\Base;
use TRegx\CleanRegex\Internal\Subject;

class MatchOffsetStream implements Upstream
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
            return \array_map(function (int $offset): int {
                return ByteOffset::toCharacterOffset($this->subject, $offset);
            }, $matches->getLimitedGroupOffsets(0, -1));
        }
        throw new UnmatchedStreamException();
    }

    protected function firstValue(): int
    {
        $match = $this->base->matchOffset();
        if ($match->matched()) {
            return ByteOffset::toCharacterOffset($this->subject, $match->byteOffset());
        }
        throw new SubjectStreamRejectedException(new FirstMatchOffsetMessage(), $this->subject);
    }
}

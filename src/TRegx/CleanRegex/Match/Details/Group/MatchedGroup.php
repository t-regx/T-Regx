<?php
namespace TRegx\CleanRegex\Match\Details\Group;

use TRegx\CleanRegex\Exception\CleanRegex\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\ByteOffset;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupDetails;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchedGroupOccurrence;

class MatchedGroup implements MatchGroup
{
    /** @var GroupDetails */
    private $details;

    /** @var MatchedGroupOccurrence */
    private $occurrence;

    public function __construct(GroupDetails $details, MatchedGroupOccurrence $matchedDetails)
    {
        $this->details = $details;
        $this->occurrence = $matchedDetails;
    }

    public function text(): string
    {
        return $this->occurrence->text;
    }

    public function matches(): bool
    {
        return true;
    }

    public function index(): int
    {
        return $this->details->index;
    }

    public function name(): ?string
    {
        return $this->details->name;
    }

    public function offset(): int
    {
        return ByteOffset::toCharacterOffset($this->occurrence->subject->getSubject(), $this->occurrence->offset);
    }

    public function byteOffset(): int
    {
        return $this->occurrence->offset;
    }

    public function __toString(): string
    {
        return $this->text();
    }

    public function all(): array
    {
        return $this->details->matchAll->all();
    }

    /**
     * @param string $exceptionClassName
     * @return mixed
     * @throws \Throwable|SubjectNotMatchedException
     */
    public function orThrow(string $exceptionClassName = GroupNotMatchedException::class): string
    {
        return $this->text();
    }

    /**
     * @param mixed $default
     * @return mixed
     */
    public function orReturn($default): string
    {
        return $this->text();
    }

    /**
     * @param callable $producer
     * @return mixed
     */
    public function orElse(callable $producer): string
    {
        return $this->text();
    }
}

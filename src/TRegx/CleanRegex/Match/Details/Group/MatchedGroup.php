<?php
namespace TRegx\CleanRegex\Match\Details\Group;

use TRegx\CleanRegex\Exception\CleanRegex\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\CleanRegex\IntegerFormatException;
use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\ByteOffset;
use TRegx\CleanRegex\Internal\Integer;
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

    public function textLength(): int
    {
        return mb_strlen($this->occurrence->text);
    }

    public function parseInt(): int
    {
        if ($this->isInt()) {
            return $this->occurrence->text;
        }
        throw IntegerFormatException::forGroup($this->details->nameOrIndex, $this->occurrence->text);
    }

    public function isInt(): bool
    {
        return Integer::isValid($this->occurrence->text);
    }

    public function matched(): bool
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

    /**
     * @return int|string
     */
    public function usedIdentifier()
    {
        return $this->details->nameOrIndex;
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
     * @param mixed $substitute
     * @return mixed
     */
    public function orReturn($substitute): string
    {
        return $this->text();
    }

    /**
     * @param callable $substituteProducer
     * @return mixed
     */
    public function orElse(callable $substituteProducer): string
    {
        return $this->text();
    }
}

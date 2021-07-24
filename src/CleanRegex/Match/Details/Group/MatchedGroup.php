<?php
namespace TRegx\CleanRegex\Match\Details\Group;

use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Internal\ByteOffset;
use TRegx\CleanRegex\Internal\Integer;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupDetails;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchedGroupOccurrence;
use TRegx\CleanRegex\Internal\Match\Details\Group\SubstitutedGroup;
use TRegx\CleanRegex\Internal\Model\Match\MatchEntry;

class MatchedGroup implements Group
{
    /** @var GroupDetails */
    private $details;
    /** @var MatchedGroupOccurrence */
    private $occurrence;
    /** @var SubstitutedGroup */
    private $substitutedGroup;

    public function __construct(MatchEntry $match, GroupDetails $details, MatchedGroupOccurrence $matchedDetails)
    {
        $this->details = $details;
        $this->occurrence = $matchedDetails;
        $this->substitutedGroup = new SubstitutedGroup($match, $matchedDetails);
    }

    public function text(): string
    {
        return $this->occurrence->text;
    }

    public function textLength(): int
    {
        return \mb_strlen($this->occurrence->text);
    }

    public function textByteLength(): int
    {
        return \strlen($this->occurrence->text);
    }

    public function toInt(): int
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

    public function equals(string $expected): bool
    {
        return $this->occurrence->text === $expected;
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

    public function tail(): int
    {
        return ByteOffset::toCharacterOffset($this->occurrence->subject->getSubject(), $this->byteTail());
    }

    public function byteOffset(): int
    {
        return $this->occurrence->offset;
    }

    public function byteTail(): int
    {
        return $this->occurrence->offset + \strlen($this->occurrence->text);
    }

    public function substitute(string $replacement): string
    {
        return $this->substitutedGroup->with($replacement);
    }

    public function subject(): string
    {
        return $this->occurrence->subject->getSubject();
    }

    public function all(): array
    {
        return $this->details->all();
    }

    public function orThrow(string $exceptionClassName = null): string
    {
        return $this->text();
    }

    public function orReturn($substitute): string
    {
        return $this->text();
    }

    public function orElse(callable $substituteProducer): string
    {
        return $this->text();
    }

    public function __toString(): string
    {
        return $this->text();
    }
}

<?php
namespace TRegx\CleanRegex\Match\Details\Group;

use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Internal\Integer;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupDetails;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupEntry;
use TRegx\CleanRegex\Internal\Match\Details\Group\SubstitutedGroup;
use TRegx\CleanRegex\Internal\Subjectable;

class MatchedGroup implements Group
{
    /** @var Subjectable */
    private $subjectable;
    /** @var GroupDetails */
    private $details;
    /** @var GroupEntry */
    private $groupEntry;
    /** @var SubstitutedGroup */
    private $substitutedGroup;

    public function __construct(Subjectable $subjectable, GroupDetails $details, GroupEntry $groupEntry, SubstitutedGroup $substitutedGroup)
    {
        $this->subjectable = $subjectable;
        $this->details = $details;
        $this->groupEntry = $groupEntry;
        $this->substitutedGroup = $substitutedGroup;
    }

    public function text(): string
    {
        return $this->groupEntry->text();
    }

    public function textLength(): int
    {
        return \mb_strlen($this->groupEntry->text());
    }

    public function textByteLength(): int
    {
        return \strlen($this->groupEntry->text());
    }

    public function toInt(): int
    {
        if ($this->isInt()) {
            return $this->groupEntry->text();
        }
        throw IntegerFormatException::forGroup($this->details->groupId, $this->groupEntry->text());
    }

    public function isInt(): bool
    {
        return Integer::isValid($this->groupEntry->text());
    }

    public function matched(): bool
    {
        return true;
    }

    public function equals(string $expected): bool
    {
        return $this->groupEntry->text() === $expected;
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
        return $this->details->groupId->nameOrIndex();
    }

    public function offset(): int
    {
        return $this->groupEntry->offset();
    }

    public function tail(): int
    {
        return $this->groupEntry->tail();
    }

    public function byteOffset(): int
    {
        return $this->groupEntry->byteOffset();
    }

    public function byteTail(): int
    {
        return $this->groupEntry->byteTail();
    }

    public function substitute(string $replacement): string
    {
        return $this->substitutedGroup->with($replacement);
    }

    public function subject(): string
    {
        return $this->subjectable->getSubject();
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

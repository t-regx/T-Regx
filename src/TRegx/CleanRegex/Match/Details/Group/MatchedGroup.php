<?php
namespace TRegx\CleanRegex\Match\Details\Group;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Internal\ByteOffset;
use TRegx\CleanRegex\Internal\Integer;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupDetails;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchedGroupOccurrence;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchGroupReplacer;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;

class MatchedGroup implements MatchGroup
{
    /** @var IRawMatchOffset */
    private $match;
    /** @var GroupDetails */
    private $details;
    /** @var MatchedGroupOccurrence */
    private $occurrence;

    public function __construct(IRawMatchOffset $match, GroupDetails $details, MatchedGroupOccurrence $matchedDetails)
    {
        $this->details = $details;
        $this->occurrence = $matchedDetails;
        $this->match = $match;
    }

    public function text(): string
    {
        return $this->occurrence->text;
    }

    public function textLength(): int
    {
        return \mb_strlen($this->occurrence->text);
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

    public function replace(string $replacement): string
    {
        return (new MatchGroupReplacer())->replaceGroup($this->match, $this->occurrence, $replacement);
    }

    public function all(): array
    {
        return $this->details->matchAll->all();
    }

    public function orThrow(string $exceptionClassName = GroupNotMatchedException::class): string
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

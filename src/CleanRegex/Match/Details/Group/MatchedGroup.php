<?php
namespace TRegx\CleanRegex\Match\Details\Group;

use TRegx\CleanRegex\Internal\Match\Details\Group\GroupDetails;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupEntry;
use TRegx\CleanRegex\Internal\Match\Details\Group\SubstitutedGroup;
use TRegx\CleanRegex\Internal\Match\Numeral\GroupExceptions;
use TRegx\CleanRegex\Internal\Match\Numeral\IntegerBase;
use TRegx\CleanRegex\Internal\Numeral\Base;
use TRegx\CleanRegex\Internal\Numeral\NumeralFormatException;
use TRegx\CleanRegex\Internal\Numeral\NumeralOverflowException;
use TRegx\CleanRegex\Internal\Numeral\StringNumeral;
use TRegx\CleanRegex\Internal\Subject;

class MatchedGroup implements Group
{
    /** @var Subject */
    private $subject;
    /** @var GroupDetails */
    private $details;
    /** @var GroupEntry */
    private $groupEntry;
    /** @var SubstitutedGroup */
    private $substitutedGroup;

    public function __construct(Subject $subject, GroupDetails $details, GroupEntry $entry, SubstitutedGroup $substituted)
    {
        $this->subject = $subject;
        $this->details = $details;
        $this->groupEntry = $entry;
        $this->substitutedGroup = $substituted;
    }

    public function text(): string
    {
        return $this->groupEntry->text();
    }

    public function toInt(int $base = 10): int
    {
        $integerBase = new IntegerBase(new Base($base), new GroupExceptions($this->details->group()));
        return $integerBase->integer($this->groupEntry->text());
    }

    public function isInt(int $base = 10): bool
    {
        $number = new StringNumeral($this->groupEntry->text());
        try {
            $number->asInt(new Base($base));
        } catch (NumeralFormatException | NumeralOverflowException $exception) {
            return false;
        }
        return true;
    }

    public function matched(): bool
    {
        return true;
    }

    public function equals(string $expected): bool
    {
        return $this->groupEntry->text() === $expected;
    }

    public function or(string $substitute): string
    {
        return $this->text();
    }

    public function name(): ?string
    {
        return $this->details->name();
    }

    public function index(): int
    {
        return $this->details->index();
    }

    /**
     * @return int|string
     */
    public function usedIdentifier()
    {
        return $this->details->nameOrIndex();
    }

    public function offset(): int
    {
        return $this->groupEntry->offset();
    }

    public function tail(): int
    {
        return $this->groupEntry->tail();
    }

    public function length(): int
    {
        return \mb_strLen($this->groupEntry->text(), 'UTF-8');
    }

    public function byteOffset(): int
    {
        return $this->groupEntry->byteOffset();
    }

    public function byteTail(): int
    {
        return $this->groupEntry->byteTail();
    }

    public function byteLength(): int
    {
        return \strLen($this->groupEntry->text());
    }

    public function subject(): string
    {
        return $this->subject;
    }

    public function all(): array
    {
        return $this->details->all();
    }

    /**
     * @deprecated
     */
    public function substitute(string $replacement): string
    {
        return $this->substitutedGroup->with($replacement);
    }

    public function __toString(): string
    {
        return $this->text();
    }
}

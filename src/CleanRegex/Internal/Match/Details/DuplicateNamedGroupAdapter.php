<?php
namespace TRegx\CleanRegex\Internal\Match\Details;

use TRegx\CleanRegex\Match\Details\Group\DuplicateNamedGroup;
use TRegx\CleanRegex\Match\Details\Group\Group;

class DuplicateNamedGroupAdapter implements DuplicateNamedGroup
{
    /** @var string */
    private $name;
    /** @var Group */
    private $group;

    public function __construct(string $name, Group $group)
    {
        $this->name = $name;
        $this->group = $group;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function text(): string
    {
        return $this->group->text();
    }

    public function length(): int
    {
        return $this->group->length();
    }

    public function byteLength(): int
    {
        return $this->group->byteLength();
    }

    public function toInt(int $base = 10): int
    {
        return $this->group->toInt($base);
    }

    public function isInt(int $base = 10): bool
    {
        return $this->group->isInt($base);
    }

    public function matched(): bool
    {
        return $this->group->matched();
    }

    public function equals(string $expected): bool
    {
        return $this->group->equals($expected);
    }

    public function usedIdentifier()
    {
        return $this->group->usedIdentifier();
    }

    public function offset(): int
    {
        return $this->group->offset();
    }

    public function tail(): int
    {
        return $this->group->tail();
    }

    public function byteOffset(): int
    {
        return $this->group->byteOffset();
    }

    public function byteTail(): int
    {
        return $this->group->byteTail();
    }

    /**
     * @deprecated
     */
    public function substitute(string $replacement): string
    {
        return $this->group->substitute($replacement);
    }

    public function subject(): string
    {
        return $this->group->subject();
    }

    public function all(): array
    {
        return $this->group->all();
    }

    public function or(string $substitute): string
    {
        return $this->group->or($substitute);
    }
}

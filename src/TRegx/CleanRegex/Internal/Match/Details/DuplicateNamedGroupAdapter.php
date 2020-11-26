<?php
namespace TRegx\CleanRegex\Internal\Match\Details;

use TRegx\CleanRegex\Match\Details\Group\DetailGroup;
use TRegx\CleanRegex\Match\Details\Group\DuplicateNamedGroup;

class DuplicateNamedGroupAdapter implements DuplicateNamedGroup
{
    /** @var string */
    private $name;
    /** @var DetailGroup */
    private $group;

    public function __construct(string $name, DetailGroup $group)
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

    public function textLength(): int
    {
        return $this->group->textLength();
    }

    public function toInt(): int
    {
        return $this->group->toInt();
    }

    public function isInt(): bool
    {
        return $this->group->isInt();
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

    public function replace(string $replacement): string
    {
        return $this->group->replace($replacement);
    }

    public function subject(): string
    {
        return $this->group->subject();
    }

    public function all(): array
    {
        return $this->group->all();
    }

    public function orThrow(string $exceptionClassName = null)
    {
        return $this->group->orThrow($exceptionClassName);
    }

    public function orReturn($substitute)
    {
        return $this->group->orReturn($substitute);
    }

    public function orElse(callable $substituteProducer)
    {
        return $this->group->orElse($substituteProducer);
    }
}

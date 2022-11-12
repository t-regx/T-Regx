<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\Numeral\Base;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Group;

class NotMatchedGroup implements Group
{
    /** @var Subject */
    private $subject;
    /** @var GroupDetails */
    private $details;

    public function __construct(Subject $subject, GroupDetails $details)
    {
        $this->subject = $subject;
        $this->details = $details;
    }

    public function text(): string
    {
        throw $this->groupNotMatched('text');
    }

    public function toInt(int $base = 10): int
    {
        new Base($base);
        throw $this->groupNotMatched('toInt');
    }

    public function isInt(int $base = 10): bool
    {
        new Base($base);
        throw $this->groupNotMatched('isInt');
    }

    public function matched(): bool
    {
        return false;
    }

    public function equals(string $expected): bool
    {
        return false;
    }

    public function or(string $substitute): string
    {
        return $substitute;
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
        throw $this->groupNotMatched('offset');
    }

    public function tail(): int
    {
        throw $this->groupNotMatched('tail');
    }

    public function length(): int
    {
        throw $this->groupNotMatched('length');
    }

    public function byteOffset(): int
    {
        throw $this->groupNotMatched('byteOffset');
    }

    public function byteTail(): int
    {
        throw $this->groupNotMatched('byteTail');
    }

    public function byteLength(): int
    {
        throw $this->groupNotMatched('byteLength');
    }

    protected function groupNotMatched(string $method): GroupNotMatchedException
    {
        return new GroupNotMatchedException("Expected to call $method() for group {$this->details->group()}, but the group was not matched");
    }

    public function subject(): string
    {
        return $this->subject;
    }

    public function all(): array
    {
        return $this->details->all();
    }
}

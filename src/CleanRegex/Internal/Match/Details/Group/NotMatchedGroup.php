<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\Numeral\Base;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Group;

/**
 * @deprecated
 */
class NotMatchedGroup implements Group
{
    /** @var Subject */
    private $subject;
    /** @var GroupDetails */
    private $details;

    /**
     * @deprecated
     */
    public function __construct(Subject $subject, GroupDetails $details)
    {
        $this->subject = $subject;
        $this->details = $details;
    }

    /**
     * @deprecated
     */
    public function text(): string
    {
        throw $this->groupNotMatched('text');
    }

    /**
     * @deprecated
     */
    public function toInt(int $base = 10): int
    {
        new Base($base);
        throw $this->groupNotMatched('toInt');
    }

    /**
     * @deprecated
     */
    public function isInt(int $base = 10): bool
    {
        new Base($base);
        throw $this->groupNotMatched('isInt');
    }

    /**
     * @deprecated
     */
    public function matched(): bool
    {
        return false;
    }

    /**
     * @deprecated
     */
    public function equals(string $expected): bool
    {
        return false;
    }

    /**
     * @deprecated
     */
    public function or(string $substitute): string
    {
        return $substitute;
    }

    /**
     * @deprecated
     */
    public function name(): ?string
    {
        return $this->details->name();
    }

    /**
     * @deprecated
     */
    public function index(): int
    {
        return $this->details->index();
    }

    /**
     * @return int|string
     * @deprecated
     */
    public function usedIdentifier()
    {
        return $this->details->nameOrIndex();
    }

    /**
     * @deprecated
     */
    public function offset(): int
    {
        throw $this->groupNotMatched('offset');
    }

    /**
     * @deprecated
     */
    public function tail(): int
    {
        throw $this->groupNotMatched('tail');
    }

    /**
     * @deprecated
     */
    public function length(): int
    {
        throw $this->groupNotMatched('length');
    }

    /**
     * @deprecated
     */
    public function byteOffset(): int
    {
        throw $this->groupNotMatched('byteOffset');
    }

    /**
     * @deprecated
     */
    public function byteTail(): int
    {
        throw $this->groupNotMatched('byteTail');
    }

    /**
     * @deprecated
     */
    public function byteLength(): int
    {
        throw $this->groupNotMatched('byteLength');
    }

    protected function groupNotMatched(string $method): GroupNotMatchedException
    {
        return new GroupNotMatchedException("Expected to call $method() for group {$this->details->group()}, but the group was not matched");
    }

    /**
     * @deprecated
     */
    public function subject(): string
    {
        return $this->subject;
    }

    /**
     * @deprecated
     */
    public function all(): array
    {
        return $this->details->all();
    }
}

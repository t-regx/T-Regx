<?php
namespace TRegx\CleanRegex\Match\Details\Group;

use Throwable;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupDetails;
use TRegx\CleanRegex\Internal\Message\GroupMessage;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\NotMatched;
use TRegx\CleanRegex\Match\Optional;

class NotMatchedGroup implements Group
{
    /** @var Subject */
    private $subject;
    /** @var GroupDetails */
    private $details;
    /** @var NotMatched */
    private $notMatched;
    /** @var GroupMessage */
    private $groupMessage;

    public function __construct(Subject $subject, GroupDetails $details, NotMatched $notMatched)
    {
        $this->subject = $subject;
        $this->details = $details;
        $this->notMatched = $notMatched;
        $this->groupMessage = new GroupMessage($this->details->group());
    }

    public function text(): string
    {
        throw $this->groupNotMatched('text');
    }

    public function textLength(): int
    {
        throw $this->groupNotMatched('textLength');
    }

    public function textByteLength(): int
    {
        throw $this->groupNotMatched('textByteLength');
    }

    public function toInt(int $base = 10): int
    {
        throw $this->groupNotMatched('toInt');
    }

    public function isInt(int $base = 10): bool
    {
        throw $this->groupNotMatched('isInt');
    }

    protected function groupNotMatched(string $method): GroupNotMatchedException
    {
        return GroupNotMatchedException::forMethod($this->details->group(), $method);
    }

    public function matched(): bool
    {
        return false;
    }

    public function equals(string $expected): bool
    {
        return false;
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

    public function byteOffset(): int
    {
        throw $this->groupNotMatched('byteOffset');
    }

    public function byteTail(): int
    {
        throw $this->groupNotMatched('byteTail');
    }

    /**
     * @deprecated
     */
    public function substitute(string $replacement): string
    {
        throw $this->groupNotMatched('substitute');
    }

    public function subject(): string
    {
        return $this->subject;
    }

    public function all(): array
    {
        return $this->details->all();
    }

    public function orReturn($substitute)
    {
        return $substitute;
    }

    public function orThrow(Throwable $throwable = null): void
    {
        if ($throwable === null) {
            throw new GroupNotMatchedException($this->groupMessage->getMessage());
        }
        throw $throwable;
    }

    public function orElse(callable $substituteProducer)
    {
        return $substituteProducer($this->notMatched);
    }

    public function map(callable $mapper): Optional
    {
        return $this;
    }
}

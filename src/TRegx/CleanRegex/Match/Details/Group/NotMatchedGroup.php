<?php
namespace TRegx\CleanRegex\Match\Details\Group;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\Factory\GroupExceptionFactory;
use TRegx\CleanRegex\Internal\Factory\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupDetails;

class NotMatchedGroup implements MatchGroup
{
    /** @var GroupDetails */
    private $details;
    /** @var GroupExceptionFactory */
    private $exceptionFactory;
    /** @var NotMatchedOptionalWorker */
    private $optionalWorker;

    public function __construct(GroupDetails $details, GroupExceptionFactory $exceptionFactory, NotMatchedOptionalWorker $optionalWorker)
    {
        $this->details = $details;
        $this->exceptionFactory = $exceptionFactory;
        $this->optionalWorker = $optionalWorker;
    }

    public function text(): string
    {
        throw $this->groupNotMatched('text');
    }

    public function textLength(): int
    {
        throw $this->groupNotMatched('textLength');
    }

    public function toInt(): int
    {
        throw $this->groupNotMatched('toInt');
    }

    public function isInt(): bool
    {
        throw $this->groupNotMatched('isInt');
    }

    protected function groupNotMatched(string $method): GroupNotMatchedException
    {
        return $this->exceptionFactory->create($method);
    }

    public function matched(): bool
    {
        return false;
    }

    public function name(): ?string
    {
        return $this->details->name;
    }

    public function index(): int
    {
        return $this->details->index;
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
        throw $this->groupNotMatched('offset');
    }

    public function byteOffset(): int
    {
        throw $this->groupNotMatched('byteOffset');
    }

    public function replace(string $replacement): string
    {
        throw $this->groupNotMatched('replace');
    }

    public function all(): array
    {
        return $this->details->matchAll->all();
    }

    public function orReturn($substitute)
    {
        return $substitute;
    }

    public function orThrow(string $exceptionClassName = GroupNotMatchedException::class): void
    {
        throw $this->optionalWorker->orThrow($exceptionClassName);
    }

    public function orElse(callable $substituteProducer)
    {
        return $this->optionalWorker->orElse($substituteProducer);
    }
}

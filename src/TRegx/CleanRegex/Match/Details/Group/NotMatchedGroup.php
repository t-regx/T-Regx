<?php
namespace TRegx\CleanRegex\Match\Details\Group;

use TRegx\CleanRegex\Exception\CleanRegex\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Factory\Group\GroupDetails;
use TRegx\CleanRegex\Internal\Factory\GroupExceptionFactory;
use TRegx\CleanRegex\Internal\Factory\NotMatchedOptionalWorker;

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

    protected function groupNotMatched(string $method): GroupNotMatchedException
    {
        return $this->exceptionFactory->create($method);
    }

    public function matches(): bool
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

    public function offset(): int
    {
        throw $this->groupNotMatched('offset');
    }

    public function __toString(): string
    {
        throw $this->groupNotMatched('__toString');
    }

    public function all(): array
    {
        return $this->details->matchAll->all();
    }

    /**
     * @param mixed $default
     * @return mixed
     */
    public function orReturn($default)
    {
        return $default;
    }

    /**
     * @param string $exceptionClassName
     * @return mixed
     * @throws \Throwable|SubjectNotMatchedException
     */
    public function orThrow(string $exceptionClassName = GroupNotMatchedException::class): string
    {
        throw $this->optionalWorker->orThrow($exceptionClassName);
    }

    /**
     * @param callable $producer
     * @return mixed
     */
    public function orElse(callable $producer)
    {
        return $this->optionalWorker->orElse($producer);
    }
}

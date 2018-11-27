<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Internal\OffsetLimit\MatchOffsetLimitFactory;
use TRegx\CleanRegex\Internal\PatternLimit;
use TRegx\CleanRegex\Match\Offset\OffsetLimit;
use function call_user_func;

class GroupLimit implements PatternLimit
{
    /** @var callable */
    private $allFactory;
    /** @var callable */
    private $firstFactory;
    /** @var MatchOffsetLimitFactory */
    private $offsetLimitFactory;

    public function __construct(callable $allFactory, callable $firstFactory, MatchOffsetLimitFactory $offsetLimitFactory)
    {
        $this->allFactory = $allFactory;
        $this->firstFactory = $firstFactory;
        $this->offsetLimitFactory = $offsetLimitFactory;
    }

    public function offsets(): OffsetLimit
    {
        return $this->offsetLimitFactory->create();
    }

    public function first(): string
    {
        return call_user_func($this->firstFactory);
    }

    /**
     * @return (string|null)[]
     */
    public function all(): array
    {
        return call_user_func($this->allFactory, -1, true);
    }

    /**
     * @param int $limit
     * @return (string|null)[]
     */
    public function only(int $limit): array
    {
        return call_user_func($this->allFactory, $limit, false);
    }
}

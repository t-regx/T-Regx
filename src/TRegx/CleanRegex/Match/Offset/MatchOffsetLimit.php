<?php
namespace TRegx\CleanRegex\Match\Offset;

use TRegx\CleanRegex\Internal\Exception\Messages\NoFirstElementFluentMessage;
use TRegx\CleanRegex\Internal\Factory\NotMatchedFluentOptionalWorker;
use TRegx\CleanRegex\Match\FluentMatchPattern;

class MatchOffsetLimit implements OffsetLimit
{
    /** @var callable */
    private $allFactory;
    /** @var callable */
    private $firstFactory;

    public function __construct(callable $allFactory, callable $firstFactory)
    {
        $this->allFactory = $allFactory;
        $this->firstFactory = $firstFactory;
    }

    public function first()
    {
        return \call_user_func($this->firstFactory);
    }

    /**
     * @return (int|null)[]
     */
    public function all(): array
    {
        return \call_user_func($this->allFactory, -1, true);
    }

    /**
     * @param int $limit
     * @return (int|null)[]
     */
    public function only(int $limit): array
    {
        return \call_user_func($this->allFactory, $limit, false);
    }

    public function fluent(): FluentMatchPattern
    {
        return new FluentMatchPattern($this->all(), new NotMatchedFluentOptionalWorker(new NoFirstElementFluentMessage()));
    }
}

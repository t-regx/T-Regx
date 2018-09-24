<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Internal\PatternLimit;
use function call_user_func;

class GroupLimit implements PatternLimit
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

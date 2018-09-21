<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Internal\PatternLimit;
use InvalidArgumentException;
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

    /**
     * @return (string|null)[]
     */
    public function all(): array
    {
        return call_user_func($this->allFactory);
    }

    public function first(): ?string
    {
        return call_user_func($this->firstFactory);
    }

    /**
     * @param int $limit
     * @return (string|null)[]
     */
    public function only(int $limit): array
    {
        if ($limit < 0) {
            throw new InvalidArgumentException("Negative limit $limit");
        }
        $matches = call_user_func($this->allFactory);
        return array_slice($matches, 0, $limit);
    }
}

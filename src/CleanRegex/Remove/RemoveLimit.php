<?php
namespace CleanRegex\Remove;

use InvalidArgumentException;

class RemoveLimit
{
    /** @var callable */
    private $patternFactory;

    public function __construct(callable $patternFactory)
    {
        $this->patternFactory = $patternFactory;
    }

    public function all(): string
    {
        return call_user_func($this->patternFactory, -1);
    }

    public function first(): string
    {
        return call_user_func($this->patternFactory, 1);
    }

    public function only(int $limit): string
    {
        if ($limit < 0) {
            throw new InvalidArgumentException("Negative limit $limit");
        }
        return call_user_func($this->patternFactory, $limit);
    }
}

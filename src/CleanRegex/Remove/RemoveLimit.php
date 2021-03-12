<?php
namespace TRegx\CleanRegex\Remove;

use InvalidArgumentException;
use TRegx\CleanRegex\Internal\PatternLimit;

class RemoveLimit implements PatternLimit
{
    /** @var callable */
    private $patternFactory;

    public function __construct(callable $patternFactory)
    {
        $this->patternFactory = $patternFactory;
    }

    public function all(): string
    {
        return ($this->patternFactory)(-1);
    }

    public function first(): string
    {
        return ($this->patternFactory)(1);
    }

    public function only(int $limit): string
    {
        if ($limit < 0) {
            throw new InvalidArgumentException("Negative limit: $limit");
        }
        return ($this->patternFactory)($limit);
    }
}

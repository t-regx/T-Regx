<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Internal\PatternLimit;
use InvalidArgumentException;

class ReplaceLimit implements PatternLimit
{
    /** @var callable */
    private $patternFactory;

    public function __construct(callable $patternFactory)
    {
        $this->patternFactory = $patternFactory;
    }

    public function all(): ReplacePattern
    {
        return call_user_func($this->patternFactory, -1);
    }

    public function first(): ReplacePattern
    {
        return call_user_func($this->patternFactory, 1);
    }

    public function only(int $limit): ReplacePattern
    {
        if ($limit < 0) {
            throw new InvalidArgumentException("Negative limit $limit");
        }
        return call_user_func($this->patternFactory, $limit);
    }
}

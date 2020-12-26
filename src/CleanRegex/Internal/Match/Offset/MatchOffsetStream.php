<?php
namespace TRegx\CleanRegex\Internal\Match\Offset;

use TRegx\CleanRegex\Internal\Match\Stream\Stream;
use TRegx\CleanRegex\Match\Offset\MatchOffsetLimit;

class MatchOffsetStream implements Stream
{
    /** @var MatchOffsetLimit */
    private $limit;

    public function __construct(MatchOffsetLimit $limit)
    {
        $this->limit = $limit;
    }

    public function all(): array
    {
        return $this->limit->all();
    }

    public function first(): int
    {
        return $this->limit->first();
    }

    public function firstKey(): int
    {
        return 0;
    }
}

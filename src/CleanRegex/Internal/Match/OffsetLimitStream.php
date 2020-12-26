<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Internal\Match\Stream\Stream;
use TRegx\CleanRegex\Match\OffsetLimit;

class OffsetLimitStream implements Stream
{
    /** @var OffsetLimit */
    private $limit;

    public function __construct(OffsetLimit $limit)
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

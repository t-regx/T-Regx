<?php
namespace Test\Utils\Impl;

use TRegx\CleanRegex\Internal\Match\Stream\NoFirstStreamException;
use TRegx\CleanRegex\Internal\Match\Stream\Upstream;

class EmptyStream implements Upstream
{
    public function all(): array
    {
        return [];
    }

    public function first()
    {
        throw new NoFirstStreamException();
    }

    public function firstKey()
    {
        throw new NoFirstStreamException();
    }
}

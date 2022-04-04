<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use Throwable;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;

class GroupStreamRejectedException extends StreamRejectedException
{
    public function throwable(): Throwable
    {
        return new GroupNotMatchedException($this->exceptionMessage->getMessage());
    }
}

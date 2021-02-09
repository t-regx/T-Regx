<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

interface Stream extends ValueStream
{
    /**
     * @return string|int
     */
    public function firstKey();
}

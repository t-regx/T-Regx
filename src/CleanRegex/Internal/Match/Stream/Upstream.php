<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

interface Upstream extends ValueStream
{
    /**
     * @return string|int
     */
    public function firstKey();
}

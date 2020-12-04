<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

interface Stream
{
    public function all();

    public function first();

    /**
     * @return string|int
     */
    public function firstKey();
}

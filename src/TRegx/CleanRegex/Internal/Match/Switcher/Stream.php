<?php
namespace TRegx\CleanRegex\Internal\Match\Switcher;

interface Stream
{
    public function all();

    public function first();

    /**
     * @return string|int
     */
    public function firstKey();
}

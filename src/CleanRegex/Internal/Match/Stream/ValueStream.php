<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

interface ValueStream
{
    public function all(): array;

    public function first();
}

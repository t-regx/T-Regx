<?php
namespace Regex\Internal;

interface SeekableExpression
{
    public function position(int $position): int;
}

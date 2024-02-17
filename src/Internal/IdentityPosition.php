<?php
namespace Regex\Internal;

class IdentityPosition implements SeekableExpression
{
    public function position(int $position): int
    {
        return $position;
    }
}

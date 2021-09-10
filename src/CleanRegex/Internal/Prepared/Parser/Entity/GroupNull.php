<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

class GroupNull implements Entity
{
    use TransitiveFlags, PatternEntity;

    public function pattern(): string
    {
        return '(?:)';
    }
}

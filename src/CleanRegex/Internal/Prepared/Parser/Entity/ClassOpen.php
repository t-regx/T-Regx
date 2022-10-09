<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

class ClassOpen implements Entity
{
    use TransitiveFlags, PatternEntity;

    public function pattern(): string
    {
        return '[';
    }
}

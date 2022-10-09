<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

class ClassClose implements Entity
{
    use TransitiveFlags, PatternEntity;

    public function pattern(): string
    {
        return ']';
    }
}

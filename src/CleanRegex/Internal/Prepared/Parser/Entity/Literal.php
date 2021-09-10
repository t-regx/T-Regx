<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

class Literal implements Entity
{
    use TransitiveFlags, PatternEntity;

    /** @var string */
    private $letters;

    public function __construct(string $letters)
    {
        $this->letters = $letters;
    }

    public function pattern(): string
    {
        return $this->letters;
    }
}

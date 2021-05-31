<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

class Literal implements Entity
{
    use TransitiveFlags, QuotesRaw;

    /** @var string */
    private $letters;

    public function __construct(string $letters)
    {
        $this->letters = $letters;
    }

    public function raw(): string
    {
        return $this->letters;
    }
}

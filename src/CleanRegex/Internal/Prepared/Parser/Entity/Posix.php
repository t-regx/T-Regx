<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

class Posix implements Entity
{
    use TransitiveFlags, QuotesRaw;

    /** @var string */
    private $character;

    public function __construct(string $character)
    {
        $this->character = $character;
    }

    public function raw(): string
    {
        return $this->character;
    }
}

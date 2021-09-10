<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

class Escaped implements Entity
{
    use TransitiveFlags, PatternEntity;

    /** @var string */
    private $character;

    public function __construct(string $character)
    {
        $this->character = $character;
    }

    public function pattern(): string
    {
        return "\\$this->character";
    }
}

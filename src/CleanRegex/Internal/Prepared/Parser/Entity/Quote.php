<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

class Quote implements Entity
{
    use TransitiveFlags, PatternEntity;

    /** @var string */
    private $quote;
    /** @var bool */
    private $closed;

    public function __construct(string $quote, bool $closed)
    {
        $this->quote = $quote;
        $this->closed = $closed;
    }

    public function pattern(): string
    {
        if ($this->closed) {
            return "\Q$this->quote\E";
        }
        return "\Q$this->quote";
    }
}

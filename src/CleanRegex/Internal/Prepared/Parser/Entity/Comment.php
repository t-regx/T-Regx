<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

class Comment implements Entity
{
    use TransitiveFlags, PatternEntity;

    /** @var string */
    private $comment;
    /** @var bool */
    private $closed;

    public function __construct(string $comment, bool $closed)
    {
        $this->comment = $comment;
        $this->closed = $closed;
    }

    public function pattern(): string
    {
        if ($this->closed) {
            return "#$this->comment\n";
        }
        return "#$this->comment";
    }
}

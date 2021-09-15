<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

class GroupComment implements Entity
{
    use TransitiveFlags, PatternEntity;

    /** @var string */
    private $comment;

    public function __construct(string $comment)
    {
        $this->comment = $comment;
    }

    public function pattern(): string
    {
        return "(?#$this->comment";
    }
}

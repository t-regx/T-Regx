<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

class GroupComment implements Entity
{
    use TransitiveFlags, QuotesRaw;

    /** @var string */
    private $comment;

    public function __construct(string $comment)
    {
        $this->comment = $comment;
    }

    public function raw(): string
    {
        return "(?#$this->comment)";
    }
}

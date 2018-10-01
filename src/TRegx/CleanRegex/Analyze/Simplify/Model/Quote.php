<?php
namespace TRegx\CleanRegex\Analyze\Simplify\Model;

class Quote extends Model
{
    /** @var string */
    private $quote;

    public function __construct(string $quote)
    {
        $this->quote = $quote;
    }

    public function getContent(): string
    {
        return $this->quote;
    }
}

<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Prepared\Template\Token;

class Placeholder implements Entity
{
    use TransitiveFlags;

    /** @var Flags */
    private $flags;
    /** @var Token */
    private $token;

    public function __construct(Flags $flags, Token $token)
    {
        $this->flags = $flags;
        $this->token = $token;
    }

    public function quotable(): Quotable
    {
        return $this->token->formatAsQuotable();
    }
}

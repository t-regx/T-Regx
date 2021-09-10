<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Template\Token;
use TRegx\CleanRegex\Internal\Prepared\Word\Word;

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

    public function word(): Word
    {
        return $this->token->formatAsQuotable();
    }
}

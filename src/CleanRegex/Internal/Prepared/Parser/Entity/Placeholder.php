<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Template\Token;

class Placeholder implements Entity
{
    use TransitiveFlags;

    /** @var Token */
    private $token;

    public function __construct(Token $token)
    {
        $this->token = $token;
    }

    public function phrase(): Phrase
    {
        return $this->token->phrase();
    }
}

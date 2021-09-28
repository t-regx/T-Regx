<?php
namespace TRegx\CleanRegex\Internal\Prepared\Figure;

use TRegx\CleanRegex\Internal\Prepared\Template\NullToken;
use TRegx\CleanRegex\Internal\Prepared\Template\Token;

class SingleFigure implements CountedFigures
{
    /** @var Token|null */
    private $token;

    public function __construct(Token $token)
    {
        $this->token = $token;
    }

    public function nextToken(): Token
    {
        if ($this->token === null) {
            return new NullToken();
        }
        $token = $this->token;
        $this->token = null;
        return $token;
    }

    public function count(): int
    {
        return 1;
    }
}

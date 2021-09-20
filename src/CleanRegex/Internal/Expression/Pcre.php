<?php
namespace TRegx\CleanRegex\Internal\Expression;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Expression\Predefinition\IdentityPredefinition;
use TRegx\CleanRegex\Internal\Expression\Predefinition\Predefinition;

class Pcre implements Expression
{
    /** @var string */
    private $pcre;

    public function __construct(string $pcre)
    {
        $this->pcre = $pcre;
    }

    public function predefinition(): Predefinition
    {
        return new IdentityPredefinition(new Definition($this->pcre, $this->pcre));
    }
}

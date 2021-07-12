<?php
namespace TRegx\CleanRegex\Internal\Prepared\Expression;

use TRegx\CleanRegex\Internal\Definition;

class Pcre implements Expression
{
    /** @var string */
    private $pcre;

    public function __construct(string $pcre)
    {
        $this->pcre = $pcre;
    }

    public function definition(): Definition
    {
        return new Definition($this->pcre, $this->pcre);
    }
}

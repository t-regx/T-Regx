<?php
namespace TRegx\CleanRegex\Internal\Prepared\Expression;

use TRegx\CleanRegex\Internal\InternalPattern;

class Pcre implements Expression
{
    /** @var string */
    private $pcre;

    public function __construct(string $pcre)
    {
        $this->pcre = $pcre;
    }

    public function definition(): InternalPattern
    {
        return new InternalPattern($this->pcre, $this->pcre);
    }
}

<?php
namespace TRegx\CleanRegex\Internal\Prepared\Orthography;

use TRegx\CleanRegex\Internal\Condition;

class PcreOrthography implements Orthography
{
    /** @var string */
    private $pcre;

    public function __construct(string $pcre)
    {
        $this->pcre = $pcre;
    }

    public function spelling(Condition $condition): Spelling
    {
        return new PcreSpelling($this->pcre);
    }
}

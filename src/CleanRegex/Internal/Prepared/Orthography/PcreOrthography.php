<?php
namespace TRegx\CleanRegex\Internal\Prepared\Orthography;

class PcreOrthography implements Orthography
{
    /** @var string */
    private $pcre;

    public function __construct(string $pcre)
    {
        $this->pcre = $pcre;
    }

    public function spelling(): Spelling
    {
        return new PcreSpelling($this->pcre);
    }
}

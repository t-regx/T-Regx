<?php
namespace TRegx\CleanRegex\Internal\Prepared\Orthography;

use TRegx\CleanRegex\Internal\Condition;
use TRegx\CleanRegex\Internal\Flags;

class PcreOrthography implements Orthography
{
    /** @var PcreSpelling */
    private $pcreSpelling;

    public function __construct(string $pcre)
    {
        $this->pcreSpelling = new PcreSpelling($pcre);
    }

    public function spelling(Condition $condition): Spelling
    {
        return $this->pcreSpelling;
    }

    public function flags(): Flags
    {
        return $this->pcreSpelling->flags();
    }
}

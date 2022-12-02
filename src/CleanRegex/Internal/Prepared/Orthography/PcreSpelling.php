<?php
namespace TRegx\CleanRegex\Internal\Prepared\Orthography;

use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Delimiter\PcreString;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Parser\SubpatternFlags;

class PcreSpelling implements Spelling
{
    /** @var PcreString */
    private $pcre;

    public function __construct(string $pcre)
    {
        $this->pcre = new PcreString($pcre);
    }

    public function delimiter(): Delimiter
    {
        return new Delimiter($this->pcre->delimiter());
    }

    public function pattern(): string
    {
        return $this->pcre->pattern();
    }

    public function flags(): Flags
    {
        return Flags::from($this->pcre->flags());
    }

    public function subpatternFlags(): SubpatternFlags
    {
        return SubpatternFlags::from($this->flags());
    }
}

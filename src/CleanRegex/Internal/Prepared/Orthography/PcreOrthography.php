<?php
namespace TRegx\CleanRegex\Internal\Prepared\Orthography;

use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Delimiter\PcreString;
use TRegx\CleanRegex\Internal\Flags;

class PcreOrthography implements Orthography
{
    /** @var string */
    private $undeveloped;
    /** @var PcreString */
    private $pcre;

    public function __construct(string $pcre)
    {
        $this->undeveloped = $pcre;
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
        return new Flags($this->pcre->flags());
    }

    public function undevelopedInput(): string
    {
        return $this->undeveloped;
    }
}

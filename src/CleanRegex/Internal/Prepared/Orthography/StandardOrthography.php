<?php
namespace TRegx\CleanRegex\Internal\Prepared\Orthography;

use TRegx\CleanRegex\Internal\Delimiter\DelimiterFinder;
use TRegx\CleanRegex\Internal\Flags;

class StandardOrthography implements Orthography
{
    /** @var DelimiterFinder */
    private $finder;
    /** @var string */
    private $input;
    /** @var string */
    private $flags;

    public function __construct(string $input, string $flags)
    {
        $this->finder = new DelimiterFinder();
        $this->input = $input;
        $this->flags = $flags;
    }

    public function delimiter(): string
    {
        return $this->finder->chooseDelimiter($this->input);
    }

    public function pattern(): string
    {
        return $this->input;
    }

    public function flags(): Flags
    {
        return new Flags($this->flags);
    }

    public function undevelopedInput(): string
    {
        return $this->input;
    }
}

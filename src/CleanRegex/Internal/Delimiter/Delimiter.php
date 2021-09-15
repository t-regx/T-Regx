<?php
namespace TRegx\CleanRegex\Internal\Delimiter;

use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;

class Delimiter
{
    /** @var string */
    private $delimiter;

    public function __construct(string $delimiter)
    {
        $this->delimiter = $delimiter;
    }

    public function delimited(Phrase $phrase, Flags $flags): string
    {
        return $this->delimiter . $phrase->conjugated($this->delimiter) . $this->delimiter . $flags;
    }
}

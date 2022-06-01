<?php
namespace TRegx\CleanRegex\Internal\Pcre\Legacy\Prime;

use TRegx\CleanRegex\Internal\Model\Entry;
use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchesOffset;

class MatchesFirstEntry implements Entry
{
    /** @var string */
    private $text;
    /** @var int */
    private $byteOffset;

    public function __construct(RawMatchesOffset $matches)
    {
        [$this->text, $this->byteOffset] = $matches->getTextAndOffset(0);
    }

    public function text(): string
    {
        return $this->text;
    }

    public function byteOffset(): int
    {
        return $this->byteOffset;
    }
}

<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Feed;

class ShortestSubstring
{
    /** @var int */
    private $minLength;
    /** @var string */
    private $endString = null;

    public function closedContent(Feed $feed): string
    {
        if ($this->endString === null) {
            return $feed->content();
        }
        return $feed->subString($this->minLength) . $this->endString;
    }

    public function update(int $length, string $endString): void
    {
        if ($this->endString !== null) {
            if ($this->minLength < $length) {
                return;
            }
        }
        $this->minLength = $length;
        $this->endString = $endString;
    }
}

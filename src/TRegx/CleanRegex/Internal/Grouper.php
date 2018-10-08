<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Exception\CleanRegex\InternalCleanRegexException;

class Grouper
{
    /** @var array|string|null */
    private $match;

    public function __construct($match)
    {
        $this->match = $match;
    }

    public function getTextAndOffset(): array
    {
        if ($this->match === null) {
            return $this->unmatched();
        }
        if (is_string($this->match)) {
            return $this->fromString();
        }
        if (is_array($this->match)) {
            return $this->fromArray();
        }
        throw new InternalCleanRegexException();
    }

    private function unmatched(): array
    {
        return [null, -1];
    }

    private function fromString(): array
    {
        return [$this->match, null];
    }

    private function fromArray()
    {
        list($value, $offset) = $this->match;
        if ($offset === -1) {
            return $this->unmatched();
        }
        return $this->match;
    }

    public function getText(): ?string
    {
        list($text) = $this->getTextAndOffset();
        return $text;
    }

    public function getOffset(): ?int
    {
        list($text, $offset) = $this->getTextAndOffset();
        return $offset;
    }
}

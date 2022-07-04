<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Feed;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;

class CharacterClassCondition
{
    /** @var ShiftString */
    private $feed;

    public function __construct(ShiftString $feed)
    {
        $this->feed = $feed;
    }

    public function consumable(): bool
    {
        return $this->nextName() !== null;
    }

    public function asString(): string
    {
        $name = $this->nextName();
        if ($name === null) {
            throw new InternalCleanRegexException();
        }
        return \substr($this->feed->content(), 0, \strLen($name));
    }

    public function commit(): void
    {
        $this->feed->shift($this->asString());
    }

    private function nextName(): ?string
    {
        $names = [
            '[:alpha:]', '[:alnum:]', '[:ascii:]', '[:blank:]',
            '[:cntrl:]', '[:digit:]', '[:graph:]', '[:lower:]',
            '[:upper:]', '[:print:]', '[:punct:]', '[:space:]',
            '[:word:]', '[:xdigit:]'
        ];
        foreach ($names as $name) {
            if ($this->feed->startsWith($name)) {
                return $name;
            }
        }
        return null;
    }
}

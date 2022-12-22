<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Feed;

class PosixClassCondition
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
        return \subStr($this->feed->content(), 0, \strLen($this->nextName()));
    }

    public function commit(): void
    {
        $this->feed->shift($this->asString());
    }

    private function nextName(): ?string
    {
        if (!$this->feed->startsWith('[:')) {
            return null;
        }
        $names = [
            '[:alpha:]', '[:alnum:]', '[:ascii:]', '[:blank:]',
            '[:cntrl:]', '[:digit:]', '[:graph:]', '[:lower:]',
            '[:upper:]', '[:print:]', '[:punct:]', '[:space:]',
            '[:word:]', '[:xdigit:]',

            '[:^alpha:]', '[:^alnum:]', '[:^ascii:]', '[:^blank:]',
            '[:^cntrl:]', '[:^digit:]', '[:^graph:]', '[:^lower:]',
            '[:^upper:]', '[:^print:]', '[:^punct:]', '[:^space:]',
            '[:^word:]', '[:^xdigit:]'
        ];
        foreach ($names as $name) {
            if ($this->feed->startsWith($name)) {
                return $name;
            }
        }
        return null;
    }
}

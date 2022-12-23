<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Feed;

class PosixClassCondition
{
    /** @var ShiftString */
    private $string;

    public function __construct(ShiftString $string)
    {
        $this->string = $string;
    }

    public function consumable(): bool
    {
        return $this->nextName() !== null;
    }

    public function asString(): string
    {
        return \subStr($this->string->content(), 0, \strLen($this->nextName()));
    }

    public function commit(): void
    {
        $this->string->shift($this->asString());
    }

    private function nextName(): ?string
    {
        if (!$this->string->startsWith('[:')) {
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
            if ($this->string->startsWith($name)) {
                return $name;
            }
        }
        return null;
    }
}

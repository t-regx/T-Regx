<?php
namespace TRegx\CleanRegex\Internal\Match\Switcher;

use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Internal\Integer;

class IntStream implements Stream
{
    /** @var BaseStream */
    private $switcher;

    public function __construct(BaseStream $switcher)
    {
        $this->switcher = $switcher;
    }

    public function all(): array
    {
        return \array_map([$this, 'parseInteger'], $this->switcher->all()->getTexts());
    }

    public function first(): int
    {
        return $this->parseInteger($this->switcher->first()->getText());
    }

    private function parseInteger(string $text): int
    {
        if (Integer::isValid($text)) {
            return $text;
        }
        throw IntegerFormatException::forMatch($text);
    }

    public function firstKey(): int
    {
        return $this->switcher->firstKey();
    }
}

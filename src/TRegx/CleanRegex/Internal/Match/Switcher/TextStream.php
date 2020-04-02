<?php
namespace TRegx\CleanRegex\Internal\Match\Switcher;

class TextStream implements Stream
{
    /** @var BaseStream */
    private $switcher;

    public function __construct(BaseStream $switcher)
    {
        $this->switcher = $switcher;
    }

    public function all(): array
    {
        return $this->switcher->all()->getTexts();
    }

    public function first(): string
    {
        return $this->switcher->first()->getText();
    }

    public function firstKey(): int
    {
        return $this->switcher->firstKey();
    }
}

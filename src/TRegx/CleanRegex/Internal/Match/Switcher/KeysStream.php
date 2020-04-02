<?php
namespace TRegx\CleanRegex\Internal\Match\Switcher;

class KeysStream implements Stream
{
    /** @var Stream */
    private $switcher;

    public function __construct(Stream $switcher)
    {
        $this->switcher = $switcher;
    }

    public function all(): array
    {
        return \array_keys($this->switcher->all());
    }

    public function first()
    {
        return $this->switcher->firstKey();
    }

    public function firstKey(): int
    {
        return 0;
    }
}

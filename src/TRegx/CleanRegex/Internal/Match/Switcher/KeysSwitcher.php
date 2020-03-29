<?php
namespace TRegx\CleanRegex\Internal\Match\Switcher;

class KeysSwitcher implements Switcher
{
    /** @var Switcher */
    private $switcher;

    public function __construct(Switcher $switcher)
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

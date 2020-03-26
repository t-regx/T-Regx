<?php
namespace TRegx\CleanRegex\Internal\Match\Switcher;

class MappingSwitcher implements Switcher
{
    /** @var array */
    private $switcher;
    /** @var callable */
    private $mapper;

    public function __construct(Switcher $switcher, callable $mapper)
    {
        $this->switcher = $switcher;
        $this->mapper = $mapper;
    }

    public function all(): array
    {
        return \array_map($this->mapper, $this->switcher->all());
    }

    public function first()
    {
        $mapper = $this->mapper;
        return $mapper($this->switcher->first());
    }
}

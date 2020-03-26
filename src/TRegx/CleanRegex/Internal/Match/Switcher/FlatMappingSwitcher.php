<?php
namespace TRegx\CleanRegex\Internal\Match\Switcher;

use TRegx\CleanRegex\Internal\Match\FlatMapper;

class FlatMappingSwitcher implements Switcher
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
        return (new FlatMapper($this->switcher->all(), $this->mapper))->get();
    }

    public function first()
    {
        return (new FlatMapper([], $this->mapper))->map($this->switcher->first());
    }
}

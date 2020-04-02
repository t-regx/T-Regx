<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

class ArrayOnlyStream implements Stream
{
    /** @var array */
    private $switcher;
    /** @var callable */
    private $mapper;

    public function __construct(Stream $switcher, callable $mapper)
    {
        $this->switcher = $switcher;
        $this->mapper = $mapper;
    }

    public function all(): array
    {
        $mapper = $this->mapper;
        return $mapper($this->switcher->all());
    }

    public function first()
    {
        return $this->switcher->first();
    }

    public function firstKey()
    {
        return $this->switcher->firstKey();
    }
}

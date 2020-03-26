<?php
namespace TRegx\CleanRegex\Internal\Match\Switcher;

use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;
use TRegx\CleanRegex\Match\Details\Match;

class GroupByCallbackSwitcher implements Switcher
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
        $map = [];
        foreach ($this->switcher->all() as $element) {
            $map[$this->mapAndValidateKey(\call_user_func($this->mapper, $element))][] = $element;
        }
        return $map;
    }

    public function first()
    {
        $value = $this->switcher->first();
        $this->mapAndValidateKey(\call_user_func($this->mapper, $value));
        return $value;
    }

    public function firstKey()
    {
        return $this->mapAndValidateKey(\call_user_func($this->mapper, $this->switcher->first()));
    }

    private function mapAndValidateKey($key)
    {
        if ($key instanceof Match || $key instanceof MatchGroup) {
            return $key->text();
        }
        if (\is_int($key) || \is_string($key)) {
            return $key;
        }
        throw InvalidReturnValueException::forGroupByCallback($key);
    }
}

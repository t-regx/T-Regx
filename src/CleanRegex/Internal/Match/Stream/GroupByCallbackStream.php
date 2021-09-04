<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Internal\ValueType;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\Group\Group;

class GroupByCallbackStream implements Stream
{
    /** @var Stream */
    private $stream;
    /** @var callable */
    private $mapper;

    public function __construct(ValueStream $stream, callable $mapper)
    {
        $this->stream = $stream;
        $this->mapper = $mapper;
    }

    public function all(): array
    {
        $map = [];
        foreach ($this->stream->all() as $element) {
            $map[$this->mapAndValidateKey(($this->mapper)($element))][] = $element;
        }
        return $map;
    }

    public function first()
    {
        $value = $this->stream->first();
        $this->mapAndValidateKey(($this->mapper)($value));
        return $value;
    }

    public function firstKey()
    {
        return $this->mapAndValidateKey(($this->mapper)($this->stream->first()));
    }

    private function mapAndValidateKey($key)
    {
        if ($key instanceof Detail || $key instanceof Group) {
            return $key->text();
        }
        if (\is_int($key) || \is_string($key)) {
            return $key;
        }
        throw new InvalidReturnValueException('groupByCallback', 'int|string', (new ValueType($key)));
    }
}

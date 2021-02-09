<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\Group\DetailGroup;

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
            $map[$this->mapAndValidateKey(\call_user_func($this->mapper, $element))][] = $element;
        }
        return $map;
    }

    public function first()
    {
        $value = $this->stream->first();
        $this->mapAndValidateKey(\call_user_func($this->mapper, $value));
        return $value;
    }

    public function firstKey()
    {
        return $this->mapAndValidateKey(\call_user_func($this->mapper, $this->stream->first()));
    }

    private function mapAndValidateKey($key)
    {
        if ($key instanceof Detail || $key instanceof DetailGroup) {
            return $key->text();
        }
        if (\is_int($key) || \is_string($key)) {
            return $key;
        }
        throw InvalidReturnValueException::forGroupByCallback($key);
    }
}

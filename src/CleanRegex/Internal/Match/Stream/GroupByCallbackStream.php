<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Internal\Match\GroupByFunction;

class GroupByCallbackStream implements Upstream
{
    /** @var Upstream */
    private $stream;
    /** @var GroupByFunction */
    private $function;

    public function __construct(ValueStream $stream, GroupByFunction $function)
    {
        $this->stream = $stream;
        $this->function = $function;
    }

    public function all(): array
    {
        $map = [];
        foreach ($this->stream->all() as $element) {
            $map[$this->function->apply($element)][] = $element;
        }
        return $map;
    }

    public function first()
    {
        $value = $this->stream->first();
        $this->function->apply($value);
        return $value;
    }

    public function firstKey()
    {
        return $this->function->apply($this->stream->first());
    }
}

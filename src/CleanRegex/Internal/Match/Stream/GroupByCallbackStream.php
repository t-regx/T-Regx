<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Internal\Match\GroupByFunction;

class GroupByCallbackStream implements Upstream
{
    /** @var Upstream */
    private $upstream;
    /** @var GroupByFunction */
    private $function;

    public function __construct(Upstream $upstream, GroupByFunction $function)
    {
        $this->upstream = $upstream;
        $this->function = $function;
    }

    public function all(): array
    {
        $map = [];
        foreach ($this->upstream->all() as $element) {
            $map[$this->function->apply($element)][] = $element;
        }
        return $map;
    }

    public function first(): array
    {
        [$key, $value] = $this->upstream->first();
        return [$this->function->apply($value), $value];
    }
}

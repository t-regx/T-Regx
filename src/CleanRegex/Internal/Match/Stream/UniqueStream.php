<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

class UniqueStream implements Upstream
{
    use PreservesKey;

    /** @var Upstream */
    private $upstream;

    public function __construct(Upstream $upstream)
    {
        $this->upstream = $upstream;
    }

    public function all(): array
    {
        $distinct = [];
        foreach ($this->upstream->all() as $key => $value) {
            if (\in_array($value, $distinct, true)) {
                continue;
            }
            $distinct[$key] = $value;
        }
        return $distinct;
    }

    public function first()
    {
        return $this->upstream->first();
    }
}

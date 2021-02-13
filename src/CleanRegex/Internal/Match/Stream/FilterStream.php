<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Exception\NoFirstStreamException;
use TRegx\CleanRegex\Internal\Match\FluentPredicate;
use TRegx\CleanRegex\Internal\Match\MethodPredicate;

class FilterStream implements Stream
{
    /** @var MethodPredicate */
    private $predicate;
    /** @var Stream */
    private $stream;

    public function __construct(Stream $stream, FluentPredicate $predicate)
    {
        $this->stream = $stream;
        $this->predicate = $predicate;
    }

    public function all(): array
    {
        return \array_filter($this->stream->all(), [$this->predicate, 'test']);
    }

    public function first()
    {
        [$value, $key] = $this->getFirstAndKey();
        return $value;
    }

    public function firstKey()
    {
        [$value, $key] = $this->getFirstAndKey();
        return $key;
    }

    private function getFirstAndKey(): array
    {
        $first = $this->stream->first();
        if ($this->predicate->test($first)) {
            return [$first, $this->stream->firstKey()];
        }

        $all = $this->stream->all();
        if (empty($all)) {
            // @codeCoverageIgnoreStart
            throw new InternalCleanRegexException();
            // @codeCoverageIgnoreEnd
        }

        $allButFirst = \array_filter(\array_slice($all, 1, null, true), [$this->predicate, 'test']);

        if (empty($allButFirst)) {
            throw new NoFirstStreamException();
        }
        $value = \reset($allButFirst);
        $key = \key($allButFirst);
        return [$value, $key];
    }
}

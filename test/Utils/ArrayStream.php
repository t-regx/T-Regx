<?php
namespace Test\Utils;

use Throwable;
use TRegx\CleanRegex\Internal\Match\Stream\Base\UnmatchedStreamException;
use TRegx\CleanRegex\Internal\Match\Stream\EmptyStreamException;
use TRegx\CleanRegex\Internal\Match\Stream\Upstream;
use TRegx\CleanRegex\Match\Stream;

class ArrayStream
{
    public static function empty(): Stream
    {
        return new Stream(self::upstream([], new EmptyStreamException()));
    }

    public static function unmatched(): Stream
    {
        return new Stream(self::upstream([], new UnmatchedStreamException()));
    }

    public static function of(array $elements): Stream
    {
        if (empty($elements)) {
            throw new \AssertionError("Empty stream");
        }
        return new Stream(self::upstream($elements, new \AssertionError()));
    }

    private static function upstream(array $elements, Throwable $empty): Upstream
    {
        return new class($elements, $empty) implements Upstream {
            use Fails;

            /** @var array */
            private $elements;
            /** @var Throwable */
            private $empty;

            public function __construct(array $elements, Throwable $empty)
            {
                $this->elements = $elements;
                $this->empty = $empty;
            }

            public function all(): array
            {
                return $this->elements;
            }

            public function first(): array
            {
                if (empty($this->elements)) {
                    throw new EmptyStreamException();
                }
                $first = \key($this->elements);
                return [$first, $this->elements[$first]];
            }
        };
    }
}

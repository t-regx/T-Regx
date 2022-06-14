<?php
namespace Test\Utils\Stream;

use TRegx\CleanRegex\Internal\Match\Stream\EmptyStreamException;
use TRegx\CleanRegex\Internal\Match\Stream\Upstream;
use TRegx\CleanRegex\Match\Stream;

class ArrayStream
{
    public static function empty(): Stream
    {
        return new Stream(self::upstream([]));
    }

    public static function unmatched(): Stream
    {
        return new Stream(self::upstream([]));
    }

    public static function of(array $elements): Stream
    {
        if (empty($elements)) {
            throw new \AssertionError("Empty stream");
        }
        return new Stream(self::upstream($elements));
    }

    private static function upstream(array $elements): Upstream
    {
        return new class($elements) implements Upstream {
            /** @var array */
            private $elements;

            public function __construct(array $elements)
            {
                $this->elements = $elements;
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

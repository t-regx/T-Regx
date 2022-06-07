<?php
namespace Test\Utils\Structure;

use PHPUnit\Framework\Assert;
use TRegx\CleanRegex\Match\Details\Detail;

class Expect
{
    public static function text(string $expected): Expectation
    {
        return new class ($expected) implements Expectation {
            /** @var string */
            private $expected;

            public function __construct(string $expected)
            {
                $this->expected = $expected;
            }

            public function apply(Detail $detail): void
            {
                Assert::assertSame($this->expected, $detail->text(),
                    "Failed to assert that Detail.text() is '$this->expected'");
            }
        };
    }

    public static function subject(string $expected): Expectation
    {
        return new class ($expected) implements Expectation {
            /** @var string */
            private $expected;

            public function __construct(string $expected)
            {
                $this->expected = $expected;
            }

            public function apply(Detail $detail): void
            {
                Assert::assertSame($this->expected, $detail->subject(),
                    "Failed to assert that Detail.text() is '$this->expected'");
            }
        };
    }

    public static function offset(int $expected): Expectation
    {
        return new class ($expected) implements Expectation {
            /** @var int */
            private $expected;

            public function __construct(int $expected)
            {
                $this->expected = $expected;
            }

            public function apply(Detail $detail): void
            {
                Assert::assertSame($this->expected, $detail->offset(),
                    "Failed to assert that Detail.offset() is $this->expected");
            }
        };
    }

    public static function index(int $expected): Expectation
    {
        return new class ($expected) implements Expectation {
            /** @var int */
            private $expected;

            public function __construct(int $expected)
            {
                $this->expected = $expected;
            }

            public function apply(Detail $detail): void
            {
                Assert::assertSame($this->expected, $detail->index(),
                    "Failed to assert that Detail.index() is $this->expected");
            }
        };
    }

    public static function length(int $expected, int $byteExpected): Expectation
    {
        return new class ($expected, $byteExpected) implements Expectation {
            /** @var int */
            private $characters;
            /** @var int */
            private $bytes;

            public function __construct(int $characters, int $bytes)
            {
                $this->characters = $characters;
                $this->bytes = $bytes;
            }

            public function apply(Detail $detail): void
            {
                Assert::assertSame($this->characters, $detail->length(),
                    "Failed to assert that Detail.index() is $this->characters");
                Assert::assertSame($this->bytes, $detail->byteLength(),
                    "Failed to assert that Detail.index() is $this->bytes");
            }
        };
    }
}

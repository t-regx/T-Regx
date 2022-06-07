<?php
namespace Test\Functional\SafeRegex\_offsets;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\preg;

class pregTest extends TestCase
{
    /**
     * @test
     * @dataProvider subjectsAndOffsets
     * @param string $subject
     * @param int $offset
     * @param string $message
     */
    public function shouldMatch_throwForInvalidOffset(string $subject, int $offset, string $message)
    {
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        // when
        preg::match('/Foo/', $subject, $matches, 0, $offset);
    }

    /**
     * @test
     */
    public function shouldMatch_countBytes(): void
    {
        // given
        $twoBytesPerLetter = 'łąęśćź';

        // when
        preg::match_all('/.+/', $twoBytesPerLetter, $match, 0, 8);

        // then
        $this->assertSame('ćź', $match[0][0]);
    }

    /**
     * @test
     * @dataProvider subjectsAndOffsets
     * @param string $subject
     * @param int $offset
     * @param string $message
     */
    public function shouldMatchAll_throwForInvalidOffset(string $subject, int $offset, string $message)
    {
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        // when
        preg::match_all('/Foo/', $subject, $matches, 0, $offset);
    }

    /**
     * @test
     */
    public function shouldMatchAll_countBytes(): void
    {
        // given
        $twoBytesPerLetter = 'łąęśćź';

        // when
        preg::match_all('/.+/', $twoBytesPerLetter, $match, 0, 8);

        // then
        $this->assertSame('ćź', $match[0][0]);
    }

    public function subjectsAndOffsets(): array
    {
        return [
            ['Foo', 4, 'Overflowing offset: 4, while subject has length: 3 (bytes)'],
            ['a', 2, 'Overflowing offset: 2, while subject has length: 1 (bytes)'],
            ['', 1, 'Overflowing offset: 1, while subject has length: 0 (bytes)'],
            ['Foo', -2, 'Negative offset: -2'],
            ['', -1, 'Negative offset: -1'],
        ];
    }
}

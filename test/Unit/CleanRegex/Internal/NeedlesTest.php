<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Needles;

/**
 * @covers \TRegx\CleanRegex\Internal\Needles
 */
class NeedlesTest extends TestCase
{
    /**
     * @test
     * @dataProvider inputsAndNeedles
     * @param string $string
     * @param string[] $needles
     * @param string[] $expected
     */
    public function shouldSplit(string $string, array $needles, array $expected)
    {
        // given
        $needles = new Needles($needles);

        // when
        $result = $needles->split($string);

        // then
        $this->assertSame($expected, $result);
    }

    public function inputsAndNeedles(): array
    {
        return [
            ['Welcome)To]The{Jungle', ['}', '{', ']', ')', '('], ['Welcome', ')', 'To', ']', 'The', '{', 'Jungle']],

            [
                'Welcome%:To%The%%Jungle%::',
                ['%:', '%%', '%::'],
                ['Welcome', '%:', 'To%The', '%%', 'Jungle', '%::', ''],
            ],

            [
                'Welcome%:To%%The%%Jungle%::%',
                ['%:', '%%', '%::', '%'],
                ['Welcome', '%:', 'To', '%%', 'The', '%%', 'Jungle', '%::', '', '%', ''],
            ],

            [
                'hey%:',
                ['%:', '%::'],
                ['hey', '%:', ''],
            ],

            [
                'Łomża',
                ['Ł', 'ż'],
                ['', 'Ł', 'om', 'ż', 'a'],
            ],
        ];
    }

    /**
     * @test
     */
    public function shouldNotSplitForNoTokens(): void
    {
        // given
        $needles = new Needles([]);

        // when
        $result = $needles->split('Welcome');

        // then
        $this->assertSame(['Welcome'], $result);
    }

    /**
     * @test
     */
    public function testWhitespace(): void
    {
        // given
        $needles = new Needles(["\t"]);

        // when
        $result = $needles->split("Hi\tthere");

        // then
        $this->assertSame(['Hi', "\t", 'there'], $result);
    }

    /**
     * @test
     */
    public function testRegexpDelimiters(): void
    {
        // given
        $needles = new Needles(['#', '/']);

        // when
        $result = $needles->split('Hi/there#general');

        // then
        $this->assertSame(['Hi', '/', 'there', '#', 'general'], $result);
    }
}

<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\MultiSplitter;

/**
 * @covers \TRegx\CleanRegex\Internal\MultiSplitter
 */
class MultiSplitterTest extends TestCase
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
        $splitter = new MultiSplitter($string, $needles);

        // when
        $result = $splitter->split();

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
        $splitter = new MultiSplitter('Welcome', []);

        // when
        $result = $splitter->split();

        // then
        $this->assertSame(['Welcome'], $result);
    }
}

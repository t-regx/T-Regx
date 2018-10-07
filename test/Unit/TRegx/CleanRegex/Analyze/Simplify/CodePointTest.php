<?php
namespace Test\Unit\TRegx\CleanRegex\Analyze\Simplify;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Analyze\Simplify\CodePoint;

class CodePointTest extends TestCase
{
    /**
     * @test
     * @dataProvider indexes
     * @param string $character
     * @param int    $expectedIndex
     */
    public function shouldIndex(string $character, int $expectedIndex)
    {
        // given
        $characterIndex = new CodePoint($character);

        // when
        $index = $characterIndex->index();

        // then
        $this->assertEquals($expectedIndex, $index);
    }

    public function indexes(): array
    {
        return [
            ['ą', 261],
            ['ę', 281],
            ['ź', 378],
            ['(', 40],
            [')', 41],
            ['ß', 223],
        ];
    }
}

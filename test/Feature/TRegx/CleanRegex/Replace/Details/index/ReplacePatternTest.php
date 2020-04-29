<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\Details\index;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Match;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetIndex_replace_first()
    {
        // given
        pattern('\d+')
            ->replace('111-222-333')
            ->first()
            ->callback(function (Match $match) {
                // when
                $index = $match->index();

                // then
                $this->assertEquals(0, $index);

                // clean up
                return '';
            });
    }

    /**
     * @test
     * @dataProvider iteratingReplaceMethods
     * @param string $method
     * @param array $arguments
     */
    public function shouldGetIndex_replace(string $method, array $arguments)
    {
        // given
        $indexes = [];

        pattern('\d+')
            ->replace('111-222-333')
            ->$method(...$arguments)
            ->callback(function (Match $match) use (&$indexes) {
                // when
                $index = $match->index();

                // then
                $indexes[] = $index;

                // clean up
                return '';
            });

        // then
        $this->assertEquals([0, 1, 2], $indexes);
    }

    public function iteratingReplaceMethods(): array
    {
        return [
            ['all', []],
            ['only', [3]],
        ];
    }
}

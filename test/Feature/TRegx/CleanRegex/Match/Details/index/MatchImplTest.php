<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\index;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

class MatchImplTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetIndex_match_first()
    {
        // given
        pattern('\d+')
            ->match('111-222-333')
            ->first(function (Detail $match) {
                // when
                $index = $match->index();

                // then
                $this->assertEquals(0, $index);
            });
    }

    /**
     * @test
     */
    public function shouldGetIndex_match_findFirst()
    {
        // given
        pattern('\d+')
            ->match('111-222-333')
            ->findFirst(function (Detail $match) {
                // when
                $index = $match->index();

                // then
                $this->assertEquals(0, $index);
            })
            ->orThrow();
    }

    /**
     * @test
     * @dataProvider iteratingMatchMethods
     * @param string $method
     */
    public function shouldGetIndex_match(string $method)
    {
        // given
        $indexes = [];

        pattern('\d+')
            ->match('111-222-333')
            ->$method(function (Detail $match) use (&$indexes) {
                // when
                $index = $match->index();
                // then
                $indexes[] = $index;
                // clean up for flatMap()
                return [];
            });

        // then
        $this->assertEquals([0, 1, 2], $indexes);
    }

    public function iteratingMatchMethods(): array
    {
        return [
            ['forEach'],
            ['map'],
            ['flatMap'],
        ];
    }
}

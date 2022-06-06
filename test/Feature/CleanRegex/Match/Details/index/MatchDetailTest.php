<?php
namespace Test\Feature\CleanRegex\Match\Details\index;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

class MatchDetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetIndex_match_first()
    {
        // given
        pattern('\d+')
            ->match('111-222-333')
            ->first(function (Detail $detail) {
                // when
                $index = $detail->index();

                // then
                $this->assertSame(0, $index);
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
            ->findFirst(function (Detail $detail) {
                // when
                $index = $detail->index();

                // then
                $this->assertSame(0, $index);
            })
            ->get();
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
            ->$method(function (Detail $detail) use (&$indexes) {
                // when
                $index = $detail->index();
                // then
                $indexes[] = $index;
                // clean up for flatMap()
                return [];
            });

        // then
        $this->assertSame([0, 1, 2], $indexes);
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

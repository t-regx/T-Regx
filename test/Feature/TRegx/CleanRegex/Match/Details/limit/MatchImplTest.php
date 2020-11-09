<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\limit;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

class MatchImplTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotBeLimited_match_forEach()
    {
        // given
        pattern('\d+')
            ->match('111-222-333')
            ->forEach(function (Detail $match) {
                // when
                $limit = $match->limit();

                // then
                $this->assertEquals(-1, $limit);
            });
    }

    /**
     * @test
     */
    public function shouldNotBeLimited_match_map()
    {
        // given
        pattern('\d+')
            ->match('111-222-333')
            ->map(function (Detail $match) {
                // when
                $limit = $match->limit();

                // then
                $this->assertEquals(-1, $limit);
            });
    }

    /**
     * @test
     */
    public function shouldNotBeLimited_match_flatMap()
    {
        // given
        pattern('\d+')
            ->match('111-222-333')
            ->flatMap(function (Detail $match) {
                // when
                $limit = $match->limit();

                // then
                $this->assertEquals(-1, $limit);

                // clean up
                return [];
            });
    }

    /**
     * @test
     */
    public function shouldBeLimited_match_first()
    {
        // given
        pattern('\d+')
            ->match('111-222-333')
            ->first(function (Detail $match) {
                // when
                $limit = $match->limit();

                // then
                $this->assertEquals(1, $limit);
            });
    }

    /**
     * @test
     */
    public function shouldBeLimited_match_findFirst()
    {
        // given
        pattern('\d+')
            ->match('111-222-333')
            ->findFirst(function (Detail $match) {
                // when
                $limit = $match->limit();

                // then
                $this->assertEquals(1, $limit);
            })
            ->orThrow();
    }
}

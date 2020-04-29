<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\limit;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Match;

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
            ->forEach(function (Match $match) {
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
            ->map(function (Match $match) {
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
            ->flatMap(function (Match $match) {
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
            ->first(function (Match $match) {
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
            ->findFirst(function (Match $match) {
                // when
                $limit = $match->limit();

                // then
                $this->assertEquals(1, $limit);
            })
            ->orThrow();
    }
}

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
    public function shouldNotBeLimited_match_iterate()
    {
        // given
        pattern('\d+')
            ->match('111-222-333')
            ->iterate(function (Match $match) {
                // when
                $limit = $match->limit();

                // then
                $this->assertEquals(-1, $limit);
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
    public function shouldBeLimited_match_forFirst()
    {
        // given
        pattern('\d+')
            ->match('111-222-333')
            ->forFirst(function (Match $match) {
                // when
                $limit = $match->limit();

                // then
                $this->assertEquals(1, $limit);
            })
            ->orThrow();
    }

    /**
     * @test
     */
    public function shouldBeLimited_replace_first()
    {
        // given
        pattern('\d+')
            ->replace('111-222-333')
            ->first()
            ->callback(function (Match $match) {
                // when
                $limit = $match->limit();

                // then
                $this->assertEquals(1, $limit);

                // clean up
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldBeLimited_replace_all()
    {
        // given
        pattern('\d+')
            ->replace('111-222-333')
            ->all()
            ->callback(function (Match $match) {
                // when
                $limit = $match->limit();

                // then
                $this->assertEquals(-1, $limit);

                // clean up
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldBeLimited_replace_only_3()
    {
        // given
        pattern('\d+')
            ->replace('111-222-333')
            ->only(3)
            ->callback(function (Match $match) {
                // when
                $limit = $match->limit();

                // then
                $this->assertEquals(3, $limit);

                // clean up
                return '';
            });
    }
}

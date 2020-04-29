<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\Details\limit;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Match;

class ReplacePatternTest extends TestCase
{
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

<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\equals;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

/**
 * @coversNothing
 */
class MatchDetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldEqual_findFirst()
    {
        // given
        pattern('Foo(Bar)')->match('FooBar')->findFirst(function (Detail $detail) {
            $this->assertTrue($detail->group(1)->equals('Bar'));
        });
    }

    /**
     * @test
     */
    public function shouldNotEqual_findFirst_forUnequal()
    {
        // given
        pattern('Foo(Bar)')->match('FooBar')->findFirst(function (Detail $detail) {
            $this->assertFalse($detail->group(1)->equals('something else'));
        });
    }

    /**
     * @test
     */
    public function shouldNotEqual_findFirst_forUnmatchedGroup()
    {
        // given
        pattern('Foo(Bar)?')->match('Foo')->findFirst(function (Detail $detail) {
            $this->assertFalse($detail->group(1)->equals('irrelevant'));
        });
    }
}

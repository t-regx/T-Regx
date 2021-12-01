<?php
namespace Test\Feature\TRegx\CleanRegex\Match\stream\_stream;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

class AbstractMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldAllKeepIndexes()
    {
        // given
        $indexes = pattern("(?:Foo|Bar|Lorem)")
            ->match("Foo, Bar, Lorem")
            ->stream()
            ->map(function (Detail $detail) {
                return $detail->index();
            })
            ->all();

        // then
        $this->assertSame([0, 1, 2], $indexes);
    }

    /**
     * @test
     */
    public function shouldAllKeepLimits()
    {
        // given
        pattern("(?:Foo|Bar)")->match("Foo, Bar")->stream()
            ->map(function (Detail $detail) {
                // then
                $this->assertSame(-1, $detail->limit());
            })
            ->all();
    }

    /**
     * @test
     */
    public function shouldGet_map_all()
    {
        // given
        $indexes = pattern("(Foo|Bar|Lorem)")
            ->match("Foo, Bar, Lorem")
            ->stream()
            ->map(function (Detail $detail) {
                return $detail->all();
            })
            ->all();

        // then
        $value = ['Foo', 'Bar', 'Lorem'];
        $this->assertSame([$value, $value, $value], $indexes);
    }

    /**
     * @test
     */
    public function shouldKeepIndex_first()
    {
        // given
        pattern("(Foo|Bar)")->match("Foo, Bar")->stream()->first(function (Detail $detail) {
            // then
            $this->assertSame(0, $detail->index());
        });
    }

    /**
     * @test
     */
    public function shouldFirst_keepLimit()
    {
        // given
        pattern("(Foo|Bar)")->match("Foo, Bar")->stream()->first(function (Detail $detail) {
            $this->assertSame(1, $detail->limit());
        });
    }

    /**
     * @test
     */
    public function shouldFirst_getAll()
    {
        // given
        $indexes = pattern("(Foo|Bar|Lorem)")
            ->match("Foo, Bar, Lorem")
            ->stream()
            ->first(function (Detail $detail) {
                return $detail->all();
            });

        // then
        $this->assertSame(['Foo', 'Bar', 'Lorem'], $indexes);
    }
}

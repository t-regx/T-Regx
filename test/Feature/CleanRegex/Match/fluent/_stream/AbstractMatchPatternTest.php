<?php
namespace Test\Feature\TRegx\CleanRegex\Match\fluent\_stream;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Match\Details\Detail;

/**
 * @coversNothing
 */
class AbstractMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldFluentAll_keepIndexes()
    {
        // given
        $indexes = pattern("(?:Foo|Bar|Lorem)")
            ->match("Foo, Bar, Lorem")
            ->fluent()
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
    public function shouldFluentAll_keepLimits()
    {
        // given
        pattern("(?:Foo|Bar)")->match("Foo, Bar")->fluent()
            ->map(function (Detail $detail) {
                // then
                $this->assertSame(-1, $detail->limit());
            })
            ->all();
    }

    /**
     * @test
     */
    public function shouldFluentAll_preserveUserData()
    {
        // given
        pattern("(Foo|Bar|Lorem)")
            ->match("Foo, Bar, Lorem")
            ->remaining(function (Detail $detail) {
                // when
                $detail->setUserData('Foo');

                // cleanup
                return true;
            })
            ->fluent()
            ->forEach(function (Detail $detail) {
                $this->assertSame('Foo', $detail->getUserData());
            });
    }

    /**
     * @test
     */
    public function shouldFluentAll_getAll()
    {
        // given
        $indexes = pattern("(Foo|Bar|Lorem)")
            ->match("Foo, Bar, Lorem")
            ->fluent()
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
    public function shouldFluentFirst_keepIndex()
    {
        // given
        pattern("(Foo|Bar)")->match("Foo, Bar")->fluent()->first(function (Detail $detail) {
            // then
            $this->assertSame(0, $detail->index());
        });
    }

    /**
     * @test
     */
    public function shouldFluentFirst_keepIndex_remaining()
    {
        // given
        pattern("(Foo|Bar)")->match("Foo, Bar")->remaining(Functions::equals('Bar'))->fluent()->first(function (Detail $detail) {
            // then
            $this->assertSame(1, $detail->index());
        });
    }

    /**
     * @test
     */
    public function shouldFluentFirst_keepLimit()
    {
        // given
        pattern("(Foo|Bar)")->match("Foo, Bar")->fluent()->first(function (Detail $detail) {
            $this->assertSame(1, $detail->limit());
        });
    }

    /**
     * @test
     */
    public function shouldFluentFirst_preserveUserData()
    {
        // given
        pattern("(Foo|Bar|Lorem)")
            ->match("Foo, Bar, Lorem")
            ->remaining(function (Detail $detail) {
                // when
                $detail->setUserData('Foo');

                // cleanup
                return true;
            })
            ->fluent()
            ->first(function (Detail $detail) {
                $this->assertSame('Foo', $detail->getUserData());
            });
    }

    /**
     * @test
     */
    public function shouldFluentFirst_getAll()
    {
        // given
        $indexes = pattern("(Foo|Bar|Lorem)")
            ->match("Foo, Bar, Lorem")
            ->fluent()
            ->first(function (Detail $detail) {
                return $detail->all();
            });

        // then
        $this->assertSame(['Foo', 'Bar', 'Lorem'], $indexes);
    }
}

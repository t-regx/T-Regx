<?php
namespace Test\Feature\TRegx\CleanRegex\Match\fluent\_stream;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

class AbstractMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldFluentAll_keepIndexes()
    {
        // given
        $indexes = pattern("\w+")
            ->match("Foo, Bar, Lorem")
            ->fluent()
            ->map(function (Detail $detail) {
                return $detail->index();
            })
            ->all();

        // then
        $this->assertEquals([0, 1, 2], $indexes);
    }

    /**
     * @test
     */
    public function shouldFluentAll_keepLimits()
    {
        // given
        pattern("\w+")->match("Foo, Bar")->fluent()
            ->map(function (Detail $detail) {
                // then
                $this->assertEquals(-1, $detail->limit());
            })
            ->all();
    }

    /**
     * @test
     */
    public function shouldFluentAll_preserveUserData()
    {
        // given
        pattern("\w+")
            ->match("Foo, Bar, Lorem")
            ->filter(function (Detail $detail) {
                // when
                $detail->setUserData('Foo');

                // cleanup
                return true;
            })
            ->fluent()
            ->forEach(function (Detail $detail) {
                $this->assertEquals('Foo', $detail->getUserData());
            });
    }

    /**
     * @test
     */
    public function shouldFluentAll_getAll()
    {
        // given
        $indexes = pattern("\w+")
            ->match("Foo, Bar, Lorem")
            ->fluent()
            ->map(function (Detail $detail) {
                return $detail->all();
            })
            ->all();

        // then
        $value = ['Foo', 'Bar', 'Lorem'];
        $this->assertEquals([$value, $value, $value], $indexes);
    }

    /**
     * @test
     */
    public function shouldFluentFirst_keepIndex()
    {
        // given
        pattern("\w+")->match("Foo, Bar")->fluent()->first(function (Detail $detail) {
            // then
            $this->assertEquals(0, $detail->index());
        });
    }

    /**
     * @test
     */
    public function shouldFluentFirst_keepLimit()
    {
        // given
        pattern("\w+")->match("Foo, Bar")->fluent()->first(function (Detail $detail) {
            $this->assertEquals(1, $detail->limit());
        });
    }

    /**
     * @test
     */
    public function shouldFluentFirst_preserveUserData()
    {
        // given
        pattern("\w+")
            ->match("Foo, Bar, Lorem")
            ->filter(function (Detail $detail) {
                // when
                $detail->setUserData('Foo');

                // cleanup
                return true;
            })
            ->fluent()
            ->first(function (Detail $detail) {
                $this->assertEquals('Foo', $detail->getUserData());
            });
    }

    /**
     * @test
     */
    public function shouldFluentFirst_getAll()
    {
        // given
        $indexes = pattern("\w+")
            ->match("Foo, Bar, Lorem")
            ->fluent()
            ->first(function (Detail $detail) {
                return $detail->all();
            });

        // then
        $this->assertEquals(['Foo', 'Bar', 'Lorem'], $indexes);
    }
}

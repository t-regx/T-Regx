<?php
namespace Test\Feature\TRegx\CleanRegex\Match\fluent\_stream;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Match;

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
            ->map(function (Match $match) {
                return $match->index();
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
            ->map(function (Match $match) {
                // then
                $this->assertEquals(-1, $match->limit());
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
            ->filter(function (Match $match) {
                // when
                $match->setUserData('Foo');

                // cleanup
                return true;
            })
            ->fluent()
            ->forEach(function (Match $match) {
                $this->assertEquals('Foo', $match->getUserData());
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
            ->map(function (Match $match) {
                return $match->all();
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
        pattern("\w+")->match("Foo, Bar")->fluent()->first(function (Match $match) {
            // then
            $this->assertEquals(0, $match->index());
        });
    }

    /**
     * @test
     */
    public function shouldFluentFirst_keepLimit()
    {
        // given
        pattern("\w+")->match("Foo, Bar")->fluent()->first(function (Match $match) {
            $this->assertEquals(1, $match->limit());
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
            ->filter(function (Match $match) {
                // when
                $match->setUserData('Foo');

                // cleanup
                return true;
            })
            ->fluent()
            ->first(function (Match $match) {
                $this->assertEquals('Foo', $match->getUserData());
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
            ->first(function (Match $match) {
                return $match->all();
            });

        // then
        $this->assertEquals(['Foo', 'Bar', 'Lorem'], $indexes);
    }
}

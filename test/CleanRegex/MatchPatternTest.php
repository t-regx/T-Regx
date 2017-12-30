<?php
namespace CleanRegex;

use CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use CleanRegex\Match\Match;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAllMatches()
    {
        // when
        $matches = pattern('Foo (B(ar))')->match('Foo Bar, Foo Bar, Foo Bar')->all();

        // then
        $this->assertEquals(['Foo Bar', 'Foo Bar', 'Foo Bar'], $matches);
    }

    /**
     * @test
     */
    public function shouldMatchAllForFirst()
    {
        // when
        pattern('(?<capital>[A-Z])(?<lowercase>[a-z]+)')
            ->match('Foo, Leszek Ziom, Dupa')
            ->first(function (Match $match) {

                // then
                $this->assertEquals(['Foo', 'Leszek', 'Ziom', 'Dupa'], $match->all());

            });
    }

    /**
     * @test
     */
    public function shouldThrowOnMissingGroup()
    {
        // then
        $this->expectException(NonexistentGroupException::class);

        // when
        pattern('(?<one>hello)')
            ->match('hello')
            ->first(function (Match $match) {
                $match->group('two');
            });
    }

    /**
     * @test
     */
    public function shouldNotCallIterateOnUnmatchedPattern()
    {
        // given
        pattern('dont match me')
            ->match('word')
            ->iterate(function () {

                // then
                $this->assertTrue(false, "This shouldn't be invoked");
            });

        // then
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function shouldNotCallFirstOnUnmatchedPattern()
    {
        // given
        pattern('dont match me')
            ->match('word')
            ->first(function () {

                // then
                $this->assertTrue(false, "This shouldn't be invoked");
            });

        // then
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function shouldGetGroupNames()
    {
        // given
        pattern('(?<one>first) and (?<two>second)')
            ->match('first and second')
            ->first(function (Match $match) {

                // when
                $groupNames = $match->groupNames();

                // then
                $this->assertEquals(['one', 'two'], $groupNames);
            });
    }

    /**
     * @test
     */
    public function shouldValidateGroupName()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Group index can only be an integer or string');

        // given
        pattern('(?<one>first) and (?<two>second)')
            ->match('first and second')
            ->first(function (Match $match) {

                // when
                $match->group(true);
            });
    }
}

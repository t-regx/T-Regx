<?php
namespace Test\Integration\CleanRegex;

use CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;
use CleanRegex\Match\Details\Match;
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
    public function shouldGetAllMatches_only()
    {
        // when
        $matches = pattern('Foo (B(ar))')->match('Foo Bar, Foo Bar, Foo Bar')->only(2);

        // then
        $this->assertEquals(['Foo Bar', 'Foo Bar'], $matches);
    }

    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // when
        $match = pattern('Foo (B(ar))')->match('Foo Bar, Foo Bar, Foo Bar')->first();

        // then
        $this->assertEquals('Foo Bar', $match);
    }

    /**
     * @test
     */
    public function shouldModifyFirstReturnValue()
    {
        // when
        $value = pattern('[A-Z]+')
            ->match('Foo, Bar, Top')
            ->first(function (Match $match) {
                return 'Different';
            });

        // then
        $this->assertEquals("Different", $value);
    }

    /**
     * @test
     */
    public function shouldAllowToReturnArbitraryType()
    {
        // when
        $value = pattern('[A-Z]+')
            ->match('Foo, Leszek Ziom, Dupa')
            ->first(function (Match $match) {
                return new \stdClass();
            });

        // then
        $this->assertInstanceOf(\stdClass::class, $value);
    }

    public function shouldMatchAllForFirst()
    {
        // when
        pattern('(?<capital>[A-Z])(?<lowercase>[a-z]+)')
            ->match('Foo, Leszek Ziom, Bar')
            ->first(function (Match $match) {
                // then
                $this->assertEquals(['Foo', 'Leszek', 'Ziom', 'Bar'], $match->all());
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
        // then
        $this->expectException(SubjectNotMatchedException::class);

        // given
        pattern('dont match me')
            ->match('word')
            ->first(function () {
                // then
                $this->assertTrue(false, "This shouldn't be invoked");
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
    public function shouldGetEmptyMatchedGroup()
    {
        // when
        $groups = pattern('([a-z]+)(?:\((\d*)\))?')
            ->match('sin(20) + cos() + tan')
            ->map(function (Match $match) {
                return $match->groups();
            });

        // then
        $expected = [
            ['sin', '20'], // braces value
            ['cos', ''],   // empty braces
            ['tan', null], // no braces
        ];
        $this->assertEquals($expected, $groups);
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
    public function shouldGetGroups()
    {
        // given
        $subject = 'Computer Leopard Three Four';

        // when
        $groups = pattern('[A-Z](?<lowercase>[a-z]+)')->match($subject)->group('lowercase')->all();

        // then
        $this->assertEquals(['omputer', 'eopard', 'hree', 'our'], $groups);
    }

    /**
     * @test
     */
    public function shouldValidateGroupName()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Group index can only be an integer or string, given: boolean (true)');

        // given
        pattern('(?<one>first) and (?<two>second)')
            ->match('first and second')
            ->first(function (Match $match) {
                // when
                $match->group(true);
            });
    }
}

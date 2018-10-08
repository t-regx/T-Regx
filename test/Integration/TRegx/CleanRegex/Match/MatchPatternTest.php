<?php
namespace Test\Integration\TRegx\CleanRegex\Match;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;
use TRegx\CleanRegex\Match\Details\Match;
use TRegx\CleanRegex\Match\Details\NotMatched;

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
    public function shouldModifyReturnValue_first()
    {
        // when
        $value = pattern('[A-Z]+')->match('Foo, Bar, Top')->first(function () {
            return 'Different';
        });

        // then
        $this->assertEquals("Different", $value);
    }

    /**
     * @test
     */
    public function shouldModifyReturnValue_forFirst()
    {
        // when
        $value = pattern('[A-Z]+')
            ->match('Foo, Bar, Top')
            ->forFirst(function () {
                return 'Different';
            })
            ->orElse(function () {
                $this->assertFalse(true);
            });

        // then
        $this->assertEquals("Different", $value);
    }

    /**
     * @test
     */
    public function shouldGetGroupsCount_forNoGroups()
    {
        // when
        $value = pattern('[a-z]+')
            ->match('NOT MATCHING')
            ->forFirst(function () {
                $this->assertFalse(true);
            })
            ->orElse(function (NotMatched $notMatched) {
                // then
                $this->assertEquals(0, $notMatched->groupsCount());
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
            ->first(function () {
                return new \stdClass();
            });

        // then
        $this->assertInstanceOf(\stdClass::class, $value);
    }

    /**
     * @test
     */
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
    public function shouldGetGroups_first()
    {
        // given
        $subject = 'Computer L Three Four';

        // when
        $groups = pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->first();

        // then
        $this->assertEquals('omputer', $groups);
    }

    /**
     * @test
     */
    public function shouldGetGroups_all()
    {
        // given
        $subject = 'Computer L Three Four';

        // when
        $groups = pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->all();

        // then
        $this->assertEquals(['omputer', null, 'hree', 'our'], $groups);
    }

    /**
     * @test
     */
    public function shouldGetGroups_onlyOne()
    {
        // given
        $subject = 'D Computer';

        // when
        $groups1 = pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->only(1);
        $groups2 = pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->only(2);

        // then
        $this->assertEquals([null], $groups1);
        $this->assertEquals([null, 'omputer'], $groups2);
    }

    /**
     * @test
     */
    public function shouldGet_offsets()
    {
        // given
        $offsets = pattern('[A-Z](?<lowercase>[a-z]+)?')
            ->match('xd Computer L Three Four')
            ->offsets();

        // when
        $first = $offsets->first();
        $only1 = $offsets->only(1);
        $only2 = $offsets->only(2);
        $all = $offsets->all();

        // then
        $this->assertEquals(3, $first);
        $this->assertEquals([3], $only1);
        $this->assertEquals([3, 12], $only2);
        $this->assertEquals([3, 12, 14, 20], $all);
    }

    /**
     * @test
     */
    public function shouldGetGroups_offsets()
    {
        // given
        $offsets = pattern('[A-Z](?<lowercase>[a-z]+)?')
            ->match('xd Computer L Three Four')
            ->group('lowercase')
            ->offsets();

        // when
        $first = $offsets->first();
        $only1 = $offsets->only(1);
        $only2 = $offsets->only(2);
        $all = $offsets->all();

        // then
        $this->assertEquals(4, $first);
        $this->assertEquals([4], $only1);
        $this->assertEquals([4, null], $only2);
        $this->assertEquals([4, null, 15, 21], $all);
    }
}

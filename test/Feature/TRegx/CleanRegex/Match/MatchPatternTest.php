<?php
namespace Test\Feature\TRegx\CleanRegex\Match;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NoSuchElementFluentException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Match\Details\Match;
use TRegx\CleanRegex\Match\Details\NotMatched;
use TRegx\CleanRegex\Match\FluentMatchPattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGet_all()
    {
        // when
        $matches = pattern('Foo (B(ar))')->match('Foo Bar, Foo Bar, Foo Bar')->all();

        // then
        $this->assertEquals(['Foo Bar', 'Foo Bar', 'Foo Bar'], $matches);
    }

    /**
     * @test
     */
    public function shouldGet_only_2()
    {
        // when
        $matches = pattern('Foo (B(ar))')->match('Foo Bar, Foo Bar, Foo Bar')->only(2);

        // then
        $this->assertEquals(['Foo Bar', 'Foo Bar'], $matches);
    }

    /**
     * @test
     */
    public function shouldGet_first()
    {
        // when
        $match = pattern('Foo (B(ar))')->match('Foo Bar, Foo Bar, Foo Bar')->first();

        // then
        $this->assertEquals('Foo Bar', $match);
    }

    /**
     * @test
     */
    public function shouldGet_first_callback()
    {
        // when
        $value = pattern('[A-Za-z]{4}\.')->match('What do you need? - Guns.')->first(function (Match $match) {
            return "Lots of $match";
        });

        // then
        $this->assertEquals("Lots of Guns.", $value);
    }

    /**
     * @test
     */
    public function shouldGet_first_returnArbitraryType()
    {
        // when
        $value = pattern('[A-Z]+')
            ->match('F')
            ->first(Functions::constant(new \stdClass()));

        // then
        $this->assertInstanceOf(\stdClass::class, $value);
    }

    /**
     * @test
     */
    public function shouldGet_first_matchAll()
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
    public function shouldGet_findFirst_orElse()
    {
        // when
        $value = pattern('[A-Z]+')
            ->match('FOO')
            ->findFirst(Functions::constant('Different'))
            ->orElse(Functions::fail());

        // then
        $this->assertEquals("Different", $value);
    }

    /**
     * @test
     */
    public function shouldGet_findFirst_orElse_groupsCount()
    {
        // when
        $value = pattern('[a-z]+')
            ->match('NOT MATCHING')
            ->findFirst(Functions::fail())
            ->orElse(function (NotMatched $notMatched) {
                // then
                $this->assertEquals(0, $notMatched->groupsCount());
                return 'Different';
            });

        // then
        $this->assertEquals('Different', $value);
    }

    /**
     * @test
     */
    public function shouldGet_map()
    {
        // when
        $mapped = pattern('[A-Za-z]+')->match('Foo, Bar, Top')->map(function (Match $match) {
            return str_split(strtoupper($match));
        });

        // then
        $expected = [
            ['F', 'O', 'O'],
            ['B', 'A', 'R'],
            ['T', 'O', 'P']
        ];
        $this->assertEquals($expected, $mapped);
    }

    /**
     * @test
     */
    public function shouldGet_distinct()
    {
        // when
        $mapped = pattern('[A-Za-z]+')->match('One, One, Two, One, Three, Two, One')->distinct();

        // then
        $this->assertEquals(['One', 'Two', 'Three'], $mapped);
    }

    /**
     * @test
     */
    public function shouldGet_flatMap()
    {
        // when
        $mapped = pattern('[A-Za-z]+')->match('Foo, Bar, Top')->flatMap(function (Match $match) {
            return str_split(strtoupper($match));
        });

        // then
        $this->assertEquals(['F', 'O', 'O', 'B', 'A', 'R', 'T', 'O', 'P'], $mapped);
    }

    /**
     * @test
     */
    public function shouldNotCall_forEach_onUnmatchedPattern()
    {
        // given
        pattern('dont match me')
            ->match('word')
            ->forEach(Functions::fail());

        // then
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function shouldNotCall_first_OnUnmatchedPattern()
    {
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');

        // given
        pattern('pattern')
            ->match('dont match me')
            ->first(Functions::fail());
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
    public function shouldNotCall_offsets_first_OnUnmatchedPattern()
    {
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get the first match offset, but subject was not matched");

        // given
        pattern('dont match me')
            ->match('word')
            ->offsets()
            ->first();
    }

    /**
     * @test
     */
    public function shouldThrow_asInt_first_OnUnmatchedPattern()
    {
        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage("Expected to get the first match as int, but subject was not matched");

        // given
        pattern('dont match me')
            ->match('word')
            ->asInt()
            ->first();
    }

    /**
     * @test
     */
    public function shouldThrow_asArray_first_OnUnmatchedPattern()
    {
        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage("Expected to get the first match as array, but subject was not matched");

        // given
        pattern('dont match me')
            ->match('word')
            ->asArray()
            ->first();
    }

    /**
     * @test
     */
    public function shouldThrow_asInt_findFirst_OnUnmatchedPattern()
    {
        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage("Expected to get the first match as int, but subject was not matched");

        // given
        pattern('dont match me')
            ->match('word')
            ->asInt()
            ->findFirst([$this, 'fail'])
            ->orThrow();
    }

    /**
     * @test
     */
    public function shouldThrow_findFirst_forUnmatchedGroup()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call text() for group '1', but the group was not matched");

        // given
        pattern('Foo(Bar)?')->match('Foo')->findFirst(function (Match $match) {
            return $match->group(1)->text();
        });
    }

    /**
     * @test
     */
    public function shouldCount_matched()
    {
        // when
        $count = count(pattern('Foo (B(ar))')->match('Foo Bar, Foo Bar, Foo Bar'));

        // then
        $this->assertEquals(3, $count);
    }

    /**
     * @test
     */
    public function shouldFilter_all()
    {
        // when
        $filtered = pattern('[A-Z][a-z]+')->match('First, Second, Third, Fourth, Fifth')
            ->filter(function (Match $match) {
                return strlen($match) === 5;
            })
            ->all();

        // then
        $this->assertEquals(['First', 'Third', 'Fifth'], $filtered);
    }

    /**
     * @test
     */
    public function shouldFilter_only_2()
    {
        // when
        $filtered = pattern('[A-Z][a-z]+')->match('First, Second, Third, Fourth, Fifth')
            ->filter(function (Match $match) {
                return strlen($match) === 5;
            })
            ->only(2);

        // then
        $this->assertEquals(['First', 'Third'], $filtered);
    }

    /**
     * @test
     */
    public function shouldFilter_only_1()
    {
        // when
        $filtered = pattern('[A-Z][a-z]+')->match('First, Second, Third, Fourth, Fifth')
            ->filter(function (Match $match) {
                return strlen($match) === 5;
            })
            ->only(1);

        // then
        $this->assertEquals(['First'], $filtered);
    }

    /**
     * @test
     */
    public function shouldFilter_only_1_filteredOut()
    {
        // when
        $filtered = pattern('[A-Z][a-z]+')->match('First, Second, Third, Fourth, Fifth')
            ->filter(Functions::constant(false))
            ->only(1);

        // then
        $this->assertEquals([], $filtered);
    }

    /**
     * @test
     */
    public function shouldFilter_count()
    {
        // when
        $filtered = pattern('[A-Z][a-z]+')->match('First, Second, Third, Fourth, Fifth')
            ->filter(function (Match $match) {
                return strlen($match) === 5;
            })
            ->count();

        // then
        $this->assertEquals(3, $filtered);
    }

    /**
     * @test
     */
    public function shouldFilter_first()
    {
        // when
        $filtered = pattern('[A-Z][a-z]+')->match('First, Second, Third, Fourth, Fifth')
            ->filter(function (Match $match) {
                return $match->index() > 1;
            })
            ->first();

        // then
        $this->assertEquals('Third', $filtered);
    }

    /**
     * @test
     */
    public function shouldFilter_matches_true()
    {
        // when
        $matches = pattern('[A-Z][a-z]+')->match('First, Second, Third, Fourth, Fifth')
            ->filter(function (Match $match) {
                return $match->text() === 'Fifth';
            })
            ->test();

        // then
        $this->assertTrue($matches);
    }

    /**
     * @test
     */
    public function shouldFilter_matches_false()
    {
        // when
        $matches = pattern('[A-Z][a-z]+')->match('First, Second, Third, Fourth, Fifth')
            ->filter(Functions::constant(false))
            ->test();

        // then
        $this->assertFalse($matches);
    }

    /**
     * @test
     */
    public function shouldFilter_matches_notMatched()
    {
        // when
        $matches = pattern('[A-Z][a-z]+')->match('NOT MATCHING')
            ->filter(Functions::constant(true))
            ->test();

        // then
        $this->assertFalse($matches);
    }

    /**
     * @test
     */
    public function shouldGetAllMatches_asInt()
    {
        // given
        $subject = "I’ll have two number 9s, a number 9 large, a number 6 with extra dip, a number 7, two number 45s, one with cheese, and a large soda.";

        // when
        $integers = pattern('\d+')->match($subject)->asInt()->all();

        // then
        $this->assertSame([9, 9, 6, 7, 45], $integers);
    }

    /**
     * @test
     */
    public function shouldGetAllMatches_asArray()
    {
        // given
        $subject = "Foo:14-16 Bar Lorem:18 Ipsum";

        // when
        $matches = pattern('\w+(?<number>:\d+)?(-\d+)?')->match($subject)->asArray()->all();

        // then
        $expected = [
            ['Foo:14-16', 'number' => ':14', ':14', '-16'],
            ['Bar', 'number' => null, null, null],
            ['Lorem:18', 'number' => ':18', ':18', null],
            ['Ipsum', 'number' => null, null, null],
        ];
        $this->assertSame($expected, $matches);
    }

    /**
     * @test
     */
    public function shouldGetFirstMatch_asInt()
    {
        // given
        $subject = "I’ll have two number 9s, a number 9 large, a number 6 with extra dip, a number 7, two number 45s, one with cheese, and a large soda.";

        // when
        $integer = pattern('\d+')->match($subject)->asInt()->first();

        // then
        $this->assertSame(9, $integer);
    }

    /**
     * @test
     */
    public function shouldGroupBy_group()
    {
        // given
        $subject = '12cm 14mm 13cm 19cm 18mm 2mm';

        // when
        $result = pattern('\d+(?<unit>cm|mm)')->match($subject)->groupBy('unit')->texts();

        // then
        $expected = [
            'cm' => ['12cm', '13cm', '19cm'],
            'mm' => ['14mm', '18mm', '2mm']
        ];
        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function shouldGroupBy_callback()
    {
        // given
        $subject = '12cm 14mm 13cm 19cm 18mm 2mm';

        // when
        $result = $this->defaultGroupBy($subject)->all();

        // then
        $expected = [
            'cm' => ['12cm', '13cm', '19cm'],
            'mm' => ['14mm', '18mm', '2mm']
        ];
        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function shouldGroupBy_callback_keys_all()
    {
        // given
        $subject = '12cm 14mm 13cm 19cm 18mm 2mm';

        // when
        $result = $this->defaultGroupBy($subject)->keys()->all();

        // then
        $this->assertEquals(['cm', 'mm'], $result);
    }

    /**
     * @test
     */
    public function shouldGroupBy_callback_keys_first()
    {
        // given
        $subject = '12cm 14mm 13cm 19cm 18mm 2mm';

        // when
        $result = $this->defaultGroupBy($subject)->keys()->first();

        // then
        $this->assertEquals('cm', $result);
    }

    private function defaultGroupBy(string $subject): FluentMatchPattern
    {
        return pattern('(?<value>\d+)(?<unit>cm|mm)')
            ->match($subject)
            ->fluent()
            ->groupByCallback(function (Match $match) {
                return $match->group('unit')->text();
            });
    }
}

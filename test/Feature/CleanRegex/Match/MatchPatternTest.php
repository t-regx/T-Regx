<?php
namespace Test\Feature\TRegx\CleanRegex\Match;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsSameMatches;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\NotMatched;
use TRegx\CleanRegex\Match\FluentMatchPattern;

class MatchPatternTest extends TestCase
{
    use AssertsSameMatches;

    /**
     * @test
     */
    public function shouldGet_all()
    {
        // when
        $matches = pattern('Foo (B(ar))')->match('Foo Bar, Foo Bar, Foo Bar')->all();

        // then
        $this->assertSame(['Foo Bar', 'Foo Bar', 'Foo Bar'], $matches);
    }

    /**
     * @test
     */
    public function shouldGet_only_2()
    {
        // when
        $matches = pattern('Foo (B(ar))')->match('Foo Bar, Foo Bar, Foo Bar')->only(2);

        // then
        $this->assertSame(['Foo Bar', 'Foo Bar'], $matches);
    }

    /**
     * @test
     */
    public function shouldGet_first()
    {
        // when
        $text = pattern('Foo (B(ar))')->match('Foo Bar, Foo Bar, Foo Bar')->first();

        // then
        $this->assertSame('Foo Bar', $text);
    }

    /**
     * @test
     */
    public function shouldGet_first_callback()
    {
        // when
        $value = pattern('[A-Za-z]{4}\.')->match('What do you need? - Guns.')->first(function (Detail $detail) {
            return "Lots of $detail";
        });

        // then
        $this->assertSame("Lots of Guns.", $value);
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
            ->first(function (Detail $detail) {
                // then
                $this->assertSame(['Foo', 'Leszek', 'Ziom', 'Bar'], $detail->all());
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
        $this->assertSame("Different", $value);
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
                $this->assertSame(0, $notMatched->groupsCount());
                return 'Different';
            });

        // then
        $this->assertSame('Different', $value);
    }

    /**
     * @test
     */
    public function shouldGet_map()
    {
        // when
        $mapped = pattern('[A-Za-z]+')->match('Foo, Bar, Top')->map(function (Detail $detail) {
            return str_split(strtoupper($detail));
        });

        // then
        $expected = [
            ['F', 'O', 'O'],
            ['B', 'A', 'R'],
            ['T', 'O', 'P']
        ];
        $this->assertSame($expected, $mapped);
    }

    /**
     * @test
     */
    public function shouldGet_distinct()
    {
        // when
        $mapped = pattern('[A-Za-z]+')->match('One, One, Two, One, Three, Two, One')->distinct();

        // then
        $this->assertSame(['One', 'Two', 'Three'], $mapped);
    }

    /**
     * @test
     */
    public function shouldGet_flatMap()
    {
        // when
        $mapped = pattern('[A-Za-z]+')->match('Foo, Bar, Top')->flatMap(function (Detail $detail) {
            return str_split(strtoupper($detail));
        });

        // then
        $this->assertSame(['F', 'O', 'O', 'B', 'A', 'R', 'T', 'O', 'P'], $mapped);
    }

    /**
     * @test
     */
    public function shouldGet_flatMapAssoc()
    {
        // when
        $mapped = pattern('[A-Za-z]+')->match('Docker, Duck, Foo')->flatMapAssoc(function (Detail $detail) {
            return str_split(strtoupper($detail));
        });

        // then
        $this->assertSame(['F', 'O', 'O', 'K', 'E', 'R'], $mapped);
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
        $this->assertSame(3, $first);
        $this->assertSame([3], $only1);
        $this->assertSame([3, 12], $only2);
        $this->assertSame([3, 12, 14, 20], $all);
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
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get the first match as integer, but subject was not matched");

        // given
        pattern('dont match me')
            ->match('word')
            ->asInt()
            ->first();
    }

    /**
     * @test
     */
    public function shouldThrow_asInt_findFirst_OnUnmatchedPattern()
    {
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get the first match as integer, but subject was not matched");

        // given
        pattern('dont match me')
            ->match('word')
            ->asInt()
            ->findFirst(Functions::fail())
            ->orThrow();
    }

    /**
     * @test
     */
    public function shouldThrow_asArray_first_OnUnmatchedPattern()
    {
        // then
        $this->expectException(SubjectNotMatchedException::class);
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
    public function shouldThrow_findFirst_forUnmatchedGroup()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call text() for group #1, but the group was not matched");

        // given
        pattern('Foo(Bar)?')->match('Foo')->findFirst(function (Detail $detail) {
            return $detail->group(1)->text();
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
        $this->assertSame(3, $count);
    }

    /**
     * @test
     */
    public function shouldFilter_all()
    {
        // when
        $filtered = pattern('[A-Z][a-z]+')->match('First, Second, Third, Fourth, Fifth')
            ->ignoring(function (Detail $detail) {
                return strlen($detail) === 5;
            })
            ->all();

        // then
        $this->assertSame(['First', 'Third', 'Fifth'], $filtered);
    }

    /**
     * @test
     */
    public function shouldFilter_only_2()
    {
        // when
        $filtered = pattern('[A-Z][a-z]+')->match('First, Second, Third, Fourth, Fifth')
            ->ignoring(function (Detail $detail) {
                return strlen($detail) === 5;
            })
            ->only(2);

        // then
        $this->assertSame(['First', 'Third'], $filtered);
    }

    /**
     * @test
     */
    public function shouldFilter_only_1()
    {
        // when
        $filtered = pattern('[A-Z][a-z]+')->match('First, Second, Third, Fourth, Fifth')
            ->ignoring(function (Detail $detail) {
                return strlen($detail) === 5;
            })
            ->only(1);

        // then
        $this->assertSame(['First'], $filtered);
    }

    /**
     * @test
     */
    public function shouldFilter_only_1_filteredOut()
    {
        // when
        $filtered = pattern('[A-Z][a-z]+')->match('First, Second, Third, Fourth, Fifth')
            ->ignoring(Functions::constant(false))
            ->only(1);

        // then
        $this->assertEmpty($filtered);
    }

    /**
     * @test
     */
    public function shouldFilter_count()
    {
        // when
        $filtered = pattern('[A-Z][a-z]+')->match('First, Second, Third, Fourth, Fifth')
            ->ignoring(function (Detail $detail) {
                return strlen($detail) === 5;
            })
            ->count();

        // then
        $this->assertSame(3, $filtered);
    }

    /**
     * @test
     */
    public function shouldFilter_first()
    {
        // when
        $filtered = pattern('[A-Z][a-z]+')->match('First, Second, Third, Fourth, Fifth')
            ->ignoring(function (Detail $detail) {
                return $detail->index() > 1;
            })
            ->first();

        // then
        $this->assertSame('Third', $filtered);
    }

    /**
     * @test
     */
    public function shouldFilter_matches_true()
    {
        // when
        $matches = pattern('[A-Z][a-z]+')->match('First, Second, Third, Fourth, Fifth')
            ->ignoring(function (Detail $detail) {
                return $detail->text() === 'Fifth';
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
            ->ignoring(Functions::constant(false))
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
            ->ignoring(Functions::constant(true))
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
        $subject = "foo:14-16 bar lorem:18 ipsum";

        // when
        $matches = pattern('[a-z]+(?<number>:\d+)?(-\d+)?')->match($subject)->asArray()->all();

        // then
        $expected = [
            ['foo:14-16', 'number' => ':14', ':14', '-16'],
            ['bar', 'number' => null, null, null],
            ['lorem:18', 'number' => ':18', ':18', null],
            ['ipsum', 'number' => null, null, null],
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
        $result = pattern('\d+(?<unit>cm|mm)')->match($subject)->groupBy('unit')->all();

        // then
        $expected = [
            'cm' => ['12cm', '13cm', '19cm'],
            'mm' => ['14mm', '18mm', '2mm']
        ];
        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function shouldGroupBy_callback()
    {
        // given
        $subject = '12cm 14mm 13cm 19cm 18mm 2mm';

        // when
        $result = $this->fluentGroupByCallback($subject)->all();

        // then
        $expected = [
            'cm' => ['12cm', '13cm', '19cm'],
            'mm' => ['14mm', '18mm', '2mm']
        ];
        $this->assertSameMatches($expected, $result);
    }

    /**
     * @test
     */
    public function shouldGroupBy_callback_keys_all()
    {
        // given
        $subject = '12cm 14mm 13cm 19cm 18mm 2mm';

        // when
        $result = $this->fluentGroupByCallback($subject)->keys()->all();

        // then
        $this->assertSame(['cm', 'mm'], $result);
    }

    /**
     * @test
     */
    public function shouldGroupBy_callback_keys_first()
    {
        // given
        $subject = '12cm 14mm 13cm 19cm 18mm 2mm';

        // when
        $result = $this->fluentGroupByCallback($subject)->keys()->first();

        // then
        $this->assertSame('cm', $result);
    }

    private function fluentGroupByCallback(string $subject): FluentMatchPattern
    {
        return pattern('(?<value>\d+)(?<unit>cm|mm)')
            ->match($subject)
            ->fluent()
            ->groupByCallback(function (Detail $detail) {
                return $detail->get('unit');
            });
    }

    /**
     * @test
     */
    public function shouldGroupByCallback()
    {
        // given
        $subject = '12cm 14mm 13cm 19cm 18mm 2mm';

        // when
        $result = pattern('(?<value>\d+)(?<unit>cm|mm)')->match($subject)->groupByCallback(function (Detail $detail) {
            return $detail->get('unit');
        });

        // then
        $expected = [
            'cm' => ['12cm', '13cm', '19cm'],
            'mm' => ['14mm', '18mm', '2mm']
        ];
        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function shouldGetNth()
    {
        // given
        $subject = '12cm 14mm 13cm 19cm';

        // when
        $result = pattern('\d+(cm|mm)')->match($subject)->nth(3);

        // then
        $this->assertSame('19cm', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_nth_forMissingMatch()
    {
        // given
        $subject = '12cm 14mm';

        // then
        $this->expectException(NoSuchNthElementException::class);
        $this->expectExceptionMessage("Expected to get the 6-nth match, but only 2 occurrences were matched");

        // when
        pattern('\d+(cm|mm)')->match($subject)->nth(6);
    }

    /**
     * @test
     */
    public function shouldThrow_nth_forUnmatchedSubject()
    {
        // given
        $subject = 'Lorem Ipsum';

        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get the 6-nth match, but subject was not matched");

        // when
        pattern('Not matching')->match($subject)->nth(6);
    }

    /**
     * @test
     */
    public function shouldThrow_nth_forNegativeArgument()
    {
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Negative nth: -6");

        // when
        pattern('Bar')->match('Bar')->nth(-6);
    }
}

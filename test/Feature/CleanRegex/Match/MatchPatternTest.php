<?php
namespace Test\Feature\TRegx\CleanRegex\Match;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsSameMatches;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Exception\NoSuchElementFluentException;
use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\NotMatched;
use TRegx\CleanRegex\Match\FluentMatchPattern;
use function pattern;

/**
 * @coversNothing
 */
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
    public function shouldGet_only2()
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
    public function shouldGet_first_withCallback()
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
        $mapped = pattern('[A-Za-z]+')->match('Foo, Bar, Top')->map('str_split');

        // then
        $expected = [
            ['F', 'o', 'o'],
            ['B', 'a', 'r'],
            ['T', 'o', 'p']
        ];
        $this->assertSame($expected, $mapped);
    }

    /**
     * @test
     */
    public function shouldGet_filter()
    {
        // when
        $mapped = pattern('[A-Za-z]+')->match('Foo, Bar, Top')->filter(Functions::notEquals('Bar'));

        // then
        $this->assertSame(['Foo', 'Top'], $mapped);
    }

    /**
     * @test
     */
    public function shouldThrow_filter_forInvalidReturnType()
    {
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid filter() callback return type. Expected bool, but integer (12) given');

        // when
        pattern('Foo')->match('Foo')->filter(Functions::constant(12));
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
        $mapped = pattern('[A-Za-z]+')->match('Foo, Bar, Top')->flatMap('str_split');

        // then
        $this->assertSame(['F', 'o', 'o', 'B', 'a', 'r', 'T', 'o', 'p'], $mapped);
    }

    /**
     * @test
     */
    public function shouldGet_flatMapAssoc()
    {
        // when
        $mapped = pattern('[A-Za-z]+')->match('Docker, Down, Foo')->flatMapAssoc('str_split');

        // then
        $this->assertSame(['F', 'o', 'o', 'n', 'e', 'r'], $mapped);
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
        pattern('Foo')->match('Bar')->first(Functions::fail());
    }

    /**
     * @test
     */
    public function shouldNotCall_forEach_OnUnmatchedPattern()
    {
        // given
        pattern('Foo')->match('Bar')->forEach(Functions::fail());

        // then
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function shouldForEach_acceptKey()
    {
        // given
        $arguments = [];

        // when
        pattern('\w+')->match('Foo, Bar, Cat')
            ->forEach(function (string $argument, int $index) use (&$arguments) {
                $arguments[$argument] = $index;
            });

        // then
        $this->assertSame(['Foo' => 0, 'Bar' => 1, 'Cat' => 2], $arguments);
    }

    /**
     * @test
     */
    public function shouldForEachGroup_acceptKey()
    {
        // given
        $arguments = [];

        // when
        pattern('(\w+)')->match('Foo, Bar, Cat')
            ->group(1)
            ->forEach(function (string $argument, int $index) use (&$arguments) {
                $arguments[$argument] = $index;
            });

        // then
        $this->assertSame(['Foo' => 0, 'Bar' => 1, 'Cat' => 2], $arguments);
    }

    /**
     * @test
     */
    public function shouldGet_offsets()
    {
        // given
        $offsets = pattern('[A-Z](?<lowercase>[a-z]+)?')->match('xd Computer L Three Four')->offsets();

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
    public function shouldThrow_offsets_first_OnUnmatchedPattern()
    {
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get the first match offset, but subject was not matched");

        // given
        pattern('Foo')->match('Bar')->offsets()->first();
    }

    /**
     * @test
     */
    public function shouldThrow_asInt_findFirst_OnUnmatchedPattern_orThrow()
    {
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get the first match as integer, but subject was not matched');

        // given
        pattern('Foo')->match('Bar')->asInt()->findFirst(Functions::fail())->orThrow();
    }

    /**
     * @test
     */
    public function shouldThrow_asInt_findFirst_OnInvalidBase()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid base: 1 (supported bases 2-36, case-insensitive)');

        // given
        pattern('Foo')->match('Bar')->asInt(1)->findFirst(Functions::fail())->orThrow();
    }

    /**
     * @test
     */
    public function shouldBe_Countable()
    {
        // when
        $count = count(pattern('Foo (B(ar))')->match('Foo Bar, Foo Bar, Foo Bar'));

        // then
        $this->assertSame(3, $count);
    }

    /**
     * @test
     */
    public function shouldReturn_asInt_all()
    {
        // given
        $subject = "I'll have two number 9s, a number 9 large, a number 6 with extra dip, a number 7, two number 45s, one with cheese, and a large soda.";

        // when
        $integers = pattern('\d+')->match($subject)->asInt()->all();

        // then
        $this->assertSame([9, 9, 6, 7, 45], $integers);
    }

    /**
     * @test
     */
    public function shouldReturn_asInt_all_base16()
    {
        // when
        $integers = pattern('\w+')->match('14, fa')->asInt(16)->all();

        // then
        $this->assertSame([20, 250], $integers);
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidBase()
    {
        // given
        $subject = "I'll have two number 9s, a number 9 large, a number 6 with extra dip, a number 7, two number 45s, one with cheese, and a large soda.";

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid base: 0 (supported bases 2-36, case-insensitive)');

        // when
        pattern('\d+')->match($subject)->asInt(0)->all();
    }

    /**
     * @test
     */
    public function shouldGet_asInt_first()
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
    public function shouldGet_asInt_base4_first()
    {
        // given
        $subject = "Number 321";

        // when
        $integer = pattern('\d+')->match($subject)->asInt(4)->first();

        // then
        $this->assertSame(57, $integer);
    }

    /**
     * @test
     */
    public function shouldThrow_asInt_filter_first()
    {
        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage('Expected to get the first element from fluent pattern, but the elements feed has 0 element(s)');

        // when
        pattern('\d+')->match('12 13')->asInt()->filter(Functions::constant(false))->first();
    }

    /**
     * @test
     */
    public function shouldThrow_asInt_filter_first_OnUnmatchedSubject()
    {
        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage('Expected to get the first match as integer, but subject was not matched');

        // when
        pattern('Foo')->match('Bar')->asInt()->filter(Functions::fail())->first();
    }

    /**
     * @test
     */
    public function shouldThrow_fluent_filter_first_OnUnmatchedSubject()
    {
        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');

        // when
        pattern('Foo')->match('Bar')->fluent()->filter(Functions::fail())->first();
    }

    /**
     * @test
     */
    public function shouldThrow_asInt_first_OnUnmatchedSubject()
    {
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get the first match as integer, but subject was not matched');

        // when
        pattern('Foo')->match('Bar')->asInt()->first();
    }

    /**
     * @test
     */
    public function shouldReturn_groupBy_all()
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
    public function shouldGet_nth()
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
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get the 6-nth match, but subject was not matched");

        // when
        pattern('Not matching')->match('Lorem Ipsum')->nth(6);
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

    /**
     * @test
     */
    public function shouldMapOptional()
    {
        // when
        $result = pattern('Foo', 'i')->match('foo')
            ->findFirst(Functions::surround('*'))
            ->map('\strToUpper')
            ->orThrow();

        // then
        $this->assertSame('*FOO*', $result);
    }

    /**
     * @test
     */
    public function shouldMapOptionalEmpty()
    {
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');

        // when
        pattern('Foo')->match('bar')->findFirst(Functions::identity())->map(Functions::fail())->orThrow();
    }
}

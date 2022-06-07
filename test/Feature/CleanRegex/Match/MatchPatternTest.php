<?php
namespace Test\Feature\CleanRegex\Match;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\CausesBacktracking;
use Test\Utils\DetailFunctions;
use Test\Utils\ExactExceptionMessage;
use Test\Utils\ExampleException;
use Test\Utils\Functions;
use Test\Utils\TestCasePasses;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Exception\NoSuchStreamElementException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;
use function pattern;

class MatchPatternTest extends TestCase
{
    use TestCasePasses, CausesBacktracking, ExactExceptionMessage;

    /**
     * @test
     */
    public function shouldGetSubject()
    {
        // given
        $match = Pattern::of('Foo')->match('Not all those who wander are lost');
        // when
        $subject = $match->subject();
        // then
        $this->assertSame('Not all those who wander are lost', $subject);
    }

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
    public function shouldGet_findFirst_orElseGet_groupsCount()
    {
        // when
        $value = pattern('Foo')
            ->match('Bar')
            ->findFirst(Functions::fail())
            ->orElse(Functions::constant('Different'));
        // then
        $this->assertSame('Different', $value);
    }

    /**
     * @test
     */
    public function shouldGet_map()
    {
        // when
        $mapped = pattern('[A-Za-z]+')->match('Foo, Bar, Top')->map(Functions::letters());

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
        $mapped = pattern('\w+')->match('Foo, Bar, Top')->filter(DetailFunctions::notEquals('Bar'));

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
        $distinct = pattern('\w+')->match('One, One, Two, One, Three, Two, One')->distinct();

        // then
        $this->assertSame(['One', 'Two', 'Three'], $distinct);
    }

    /**
     * @test
     */
    public function shouldGet_flatMap()
    {
        // when
        $mapped = pattern('[A-Za-z]+')->match('Foo, Bar, Top')->flatMap(Functions::letters());

        // then
        $this->assertSame(['F', 'o', 'o', 'B', 'a', 'r', 'T', 'o', 'p'], $mapped);
    }

    /**
     * @test
     */
    public function shouldGet_flatMapAssoc()
    {
        // when
        $mapped = pattern('[A-Za-z]+')->match('Docker, Down, Foo')->flatMapAssoc(Functions::letters());

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
    public function shouldThrowSubjectNotMatched_getSubject()
    {
        // given
        try {
            pattern('Foo')->match('Bar')->first(Functions::fail());
        } catch (SubjectNotMatchedException $exception) {
            $this->assertSame('Bar', $exception->getSubject());
        }
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
    public function shouldThrow_asInt_findFirst_OnUnmatchedPattern_orThrow()
    {
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get the first match as integer, but subject was not matched');

        // given
        pattern('Foo')->match('Bar')->asInt()->findFirst(Functions::fail())->get();
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
        pattern('Foo')->match('Bar')->asInt(1)->findFirst(Functions::fail())->get();
    }

    /**
     * @test
     */
    public function shouldBe_Countable()
    {
        // given
        $match = pattern('Foo (B(ar))')->match('Foo Bar, Foo Bar, Foo Bar');

        // when
        $count = \count($match);

        // then
        $this->assertSame(3, $count);
    }

    /**
     * @test
     */
    public function shouldCount()
    {
        // given
        $count = pattern('Foo')->match('Foo, Foo')->count();

        // then
        $this->assertSame(2, $count);
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
        $subject = "I'll have two number 9s, a number 9 large, a number 6 with extra dip, a number 7, two number 45s, one with cheese, and a large soda.";

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
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first stream element, but the stream has 0 element(s)');

        // when
        pattern('\d+')->match('12 13')->asInt()->filter(Functions::constant(false))->first();
    }

    /**
     * @test
     */
    public function shouldThrow_asInt_filter_first_OnUnmatchedSubject()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first match as integer, but subject was not matched');

        // when
        pattern('Foo')->match('Bar')->asInt()->filter(Functions::fail())->first();
    }

    /**
     * @test
     */
    public function shouldThrow_stream_filter_first_OnUnmatchedSubject()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');

        // when
        pattern('Foo')->match('Bar')->stream()->filter(Functions::fail())->first();
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
        $this->expectException(NoSuchNthElementException::class);
        $this->expectExceptionMessage("Expected to get the 6-nth match, but subject was not matched at all");

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
            ->get();

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
        pattern('Foo')->match('bar')->findFirst(Functions::identity())->map(Functions::fail())->get();
    }

    /**
     * @test
     * @depends shouldMapOptionalEmpty
     */
    public function shouldMapOptionalEmptyExceptionSubject()
    {
        // given
        $optional = pattern('Foo')->match('bar')->findFirst(Functions::identity())->map(Functions::fail());
        try {
            // when
            $optional->get();
        } catch (SubjectNotMatchedException $exception) {
            // then
            $this->assertSame('bar', $exception->getSubject());
        }
    }

    /**
     * @test
     */
    public function shouldMapOptionalEmptyOrThrow()
    {
        // then
        $this->expectException(ExampleException::class);
        // when
        pattern('Foo')->match('bar')->findFirst(Functions::identity())->map(Functions::fail())->orThrow(new ExampleException());
    }

    /**
     * @test
     */
    public function shouldCallOnceForLimit1()
    {
        // when
        $only = $this->backtrackingMatch()->only(1);
        // then
        $this->assertSame(['123'], $only);
    }

    /**
     * @test
     */
    public function shouldNotCallForLimit0()
    {
        // when
        $only = $this->backtrackingMatch()->only(0);
        // then
        $this->assertSame([], $only);
    }

    /**
     * @test
     */
    public function shouldIterateTwice()
    {
        // given
        $iterator = pattern('\d+')->match('123,456,789')->getIterator();

        // when
        $firstIteration = \iterator_to_array($iterator);
        $iterator->rewind();
        $secondIteration = \iterator_to_array($iterator);

        // then
        $this->assertSame(['123', '456', '789'], $this->detailTexts($firstIteration));
        $this->assertSame(['123', '456', '789'], $this->detailTexts($secondIteration));
    }

    private function detailTexts(array $details): array
    {
        $texts = [];
        foreach ($details as $detail) {
            $texts[] = $detail->text();
        }
        return $texts;
    }

    /**
     * @test
     */
    public function shouldGetEmptyGroupNames()
    {
        // when
        $groupNames = Pattern::of('Foo')->match('Foo')->groupNames();
        // then
        $this->assertSame([], $groupNames);
    }

    /**
     * @test
     */
    public function shouldGetGroupNames()
    {
        // when
        $groupNames = Pattern::of('(?<first>first), (?<second>second)')->match('first, second')->groupNames();
        // then
        $this->assertSame(['first', 'second'], $groupNames);
    }

    /**
     * @test
     */
    public function shouldGetGroupNameUnnamed()
    {
        // when
        $groupNames = Pattern::of('(Foo)')->match('Foo')->groupNames();
        // then
        $this->assertSame([null], $groupNames);
    }

    /**
     * @test
     */
    public function shouldGetGroupNamesMixed()
    {
        // when
        $groupNames = Pattern::of('(?<name>Foo)(Missing)?')->match('Foo')->groupNames();
        // then
        $this->assertSame(['name', null], $groupNames);
    }

    /**
     * @test
     */
    public function shouldGetGroupNamesDuplicateNamed()
    {
        // when
        $groupNames = Pattern::of('(?<name>Foo)(?<name>Foo)', 'J')->match('Foo')->groupNames();
        // then
        $this->assertSame(['name', null], $groupNames);
    }

    /**
     * @test
     */
    public function shouldGroupNamesThrowForMalformedElements()
    {
        // given
        $match = Pattern::of('+')->match('Bar');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        $match->groupNames();
    }

    /**
     * @test
     */
    public function shouldGroupNamesNotCauseCatastrophicBacktracking()
    {
        // when
        $this->backtrackingMatch()->groupNames();
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldGetZeroGroups()
    {
        // when
        $count = Pattern::of('Foo')->match('Foo')->groupsCount();
        // then
        $this->assertSame(0, $count);
    }

    /**
     * @test
     */
    public function shouldGetFourGroups()
    {
        // when
        $count = Pattern::of('(Foo)()()()')->match('Foo')->groupsCount();
        // then
        $this->assertSame(4, $count);
    }

    /**
     * @test
     */
    public function shouldGetFourGroupsMixed()
    {
        // when
        $count = Pattern::of('(Foo)(?<name>)()(?<other>)')->match('Foo')->groupsCount();
        // then
        $this->assertSame(4, $count);
    }

    /**
     * @test
     */
    public function shouldGroupsCountNotCauseCatastrophicBacktracking()
    {
        // when
        $this->backtrackingMatch()->groupsCount();
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldGroupsCountThrowForMalformedElements()
    {
        // given
        $match = Pattern::of('+')->match('Bar');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        $match->groupsCount();
    }

    /**
     * @test
     */
    public function shouldPatternHaveGroup0()
    {
        $this->assertTrue(Pattern::of('Foo')->match('Foo')->groupExists(0));
        $this->assertTrue(Pattern::of('Foo')->match('Bar')->groupExists(0));
    }

    /**
     * @test
     */
    public function shouldPatternNotHaveGroup()
    {
        $this->assertFalse(Pattern::of('Foo')->match('Foo')->groupExists(1));
        $this->assertFalse(Pattern::of('Foo')->match('Bar')->groupExists(1));
    }

    /**
     * @test
     */
    public function shouldPatternHaveGroup1()
    {
        $this->assertTrue(Pattern::of('(Foo)')->match('Foo')->groupExists(1));
        $this->assertTrue(Pattern::of('(Foo)')->match('Bar')->groupExists(1));
    }

    /**
     * @test
     */
    public function shouldPatternNotHaveNonCapturingGroup()
    {
        $this->assertFalse(Pattern::of('(?:Foo)')->match('Foo')->groupExists(1));
        $this->assertFalse(Pattern::of('(?:Foo)')->match('Bar')->groupExists(1));
    }

    /**
     * @test
     */
    public function shouldPatternHaveNamedGroup()
    {
        $this->assertTrue(Pattern::of('-(?<name>Foo)')->match('Foo')->groupExists('name'));
        $this->assertTrue(Pattern::of('-(?<name>Foo)')->match('Bar')->groupExists('name'));
    }

    /**
     * @test
     */
    public function shouldPatternNotHaveNamedGroup()
    {
        $this->assertFalse(Pattern::of('-(?<name>Foo)')->match('Foo')->groupExists('missing'));
        $this->assertFalse(Pattern::of('-(?<name>Foo)')->match('Bar')->groupExists('missing'));
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedPattern()
    {
        // given
        $match = Pattern::of('+')->match('Foo');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        $match->groupExists(2);
    }

    /**
     * @test
     */
    public function shouldHasGroupNotCauseCatastrophicBacktracking()
    {
        // when
        $this->backtrackingMatch()->groupExists(2);
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedGroupName()
    {
        // given
        $match = Pattern::of('Foo')->match('Foo');
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '2group' given");
        // when
        $match->groupExists('2group');
    }

    /**
     * @test
     */
    public function shouldThrowForNegativeIndex()
    {
        // given
        $match = Pattern::of('Foo')->match('Foo');
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Group index must be a non-negative integer, but -1 given");
        // when
        $match->groupExists(-1);
    }
}

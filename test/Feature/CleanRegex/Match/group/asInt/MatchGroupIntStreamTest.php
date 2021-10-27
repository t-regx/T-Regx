<?php
namespace Test\Feature\TRegx\CleanRegex\Match\group\asInt;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsSameMatches;
use Test\Utils\ExactExceptionMessage;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\IntegerOverflowException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\NoSuchElementFluentException;
use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;

/**
 * @coversNothing
 */
class MatchGroupIntStreamTest extends TestCase
{
    use AssertsSameMatches, ExactExceptionMessage;

    /**
     * @test
     */
    public function shouldGet_asInt_map_all()
    {
        // when
        $groups = pattern('n:(\d+)')
            ->match('n:14 n:18 n:20')
            ->group(1)
            ->asInt()
            ->map(function ($number) {
                $this->assertIsInt($number);
                return "Number:$number";
            })
            ->all();

        // then
        $this->assertSame(['Number:14', 'Number:18', 'Number:20'], $groups);
    }

    /**
     * @test
     */
    public function shouldGet_asInt_map_all_base16()
    {
        // when
        $groups = pattern('n:(\w+)')->match('n:f1a n:eee n:18')->group(1)->asInt(16)->all();

        // then
        $this->assertSame([3866, 3822, 24], $groups);
    }

    /**
     * @test
     */
    public function shouldGet_all_forUnmatchedSubject()
    {
        // when
        $all = pattern('(Foo)')->match('Bar')->group(1)->asInt()->all();

        // then
        $this->assertEmpty($all);
    }

    /**
     * @test
     */
    public function shouldThrow_first_forUnmatchedSubject()
    {
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group #1 as integer from the first match, but subject was not matched at all");

        // when
        pattern('(Foo)')->match('Bar')->group(1)->asInt()->first();
    }

    /**
     * @test
     */
    public function shouldThrow_first_forInvalidBase()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid base: 1 (supported bases 2-36, case-insensitive)");

        // when
        pattern('(Foo)')->match('Bar')->group(1)->asInt(1)->first();
    }

    /**
     * @test
     */
    public function shouldThrow_first_keys_forUnmatchedSubject()
    {
        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage("Expected to get group #1 as integer from the first match, but subject was not matched at all");

        // when
        pattern('(Foo)')->match('Bar')->group(1)->asInt()->keys()->first();
    }

    /**
     * @test
     */
    public function shouldThrow_all_forNonexistentGroup()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        pattern('Foo')->match('Foo')->group('missing')->asInt()->all();
    }

    /**
     * @test
     */
    public function shouldThrow_all_forNonexistentGroup_onUnmatchedSubject()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        pattern('Foo')->match('Bar')->group('missing')->asInt()->all();
    }

    /**
     * @test
     */
    public function shouldThrow_first_forNonexistentGroup()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        pattern('Foo')->match('Foo')->group('missing')->asInt()->first();
    }

    /**
     * @test
     */
    public function shouldThrow_first_forNonexistentGroup_onUnmatchedSubject()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        pattern('Foo')->match('Bar')->group('missing')->asInt()->first();
    }

    /**
     * @test
     */
    public function shouldThrow_first_keys_forNonexistentGroup()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        pattern('Foo')->match('Foo')->group('missing')->asInt()->keys()->first();
    }

    /**
     * @test
     */
    public function shouldThrow_all_forMalformedInteger()
    {
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse group #1, but '1  ' is not a valid integer in base 10");

        // when
        pattern('"(\-?\w+\s*)"')->match('"90" "-60" "1  "')->group(1)->asInt()->all();
    }

    /**
     * @test
     */
    public function shouldThrow_all_forMalformedInteger_forEmptyString()
    {
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse group #1, but '' is not a valid integer in base 10");

        // when
        pattern('()')->match('')->group(1)->asInt()->all();
    }

    /**
     * @test
     */
    public function shouldThrow_first_forMalformedInteger()
    {
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse group #0, but 'Foo' is not a valid integer in base 10");

        // when
        pattern('Foo')->match('Foo')->group(0)->asInt()->first();
    }

    /**
     * @test
     */
    public function shouldThrow_first_keys_forMalformedInteger()
    {
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse group #0, but 'Foo' is not a valid integer in base 10");

        // when
        pattern('Foo')->match('Foo')->group(0)->asInt()->keys()->first();
    }

    /**
     * @test
     */
    public function shouldThrow_first_keys_forOverflownInteger()
    {
        // then
        $this->expectException(IntegerOverflowException::class);
        $this->expectExceptionMessage("Expected to parse group #0, but '9223372036854775809' exceeds integer size on this architecture in base 10");

        // when
        pattern('\d+')->match('-9223372036854775809')->group(0)->asInt()->keys()->first();
    }

    /**
     * @test
     */
    public function shouldThrow_first_keys_forOverflownInteger_inBase16()
    {
        // then
        $this->expectException(IntegerOverflowException::class);
        $this->expectExceptionMessage("Expected to parse group #0, but '92233720368547750000' exceeds integer size on this architecture in base 16");

        // when
        pattern('\d+')->match('-92233720368547750000')->group(0)->asInt(16)->keys()->first();
    }

    /**
     * @test
     */
    public function shouldThrow_first_keys_forOverflownIntegerNegative_inBase16()
    {
        // then
        $this->expectException(IntegerOverflowException::class);
        $this->expectExceptionMessage("Expected to parse group #0, but '-92233720368547750000' exceeds integer size on this architecture in base 16");

        // when
        pattern('-\d+')->match('-92233720368547750000')->group(0)->asInt(16)->keys()->first();
    }

    /**
     * @test
     */
    public function shouldGet_all_forUnmatchedGroup()
    {
        // when
        $groups = pattern('"(\d+)?"')->match('"12" "" "13"')->group(1)->asInt()->all();

        // then
        $this->assertSame([12, null, 13], $groups);
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidBase()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid base: 38 (supported bases 2-36, case-insensitive)');

        // when
        pattern('"(\d+)?"')->match('')->group(1)->asInt(38)->all();
    }

    /**
     * @test
     */
    public function shouldThrow_first_forUnmatchedGroup()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get group #1 as integer from the first match, but the group was not matched');

        // when
        pattern('(Foo)?')->match('')->group(1)->asInt()->first();
    }

    /**
     * @test
     */
    public function shouldThrow_first_keys_forUnmatchedGroup()
    {
        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage('Expected to get group #1 as integer from the first match, but the group was not matched');

        // when
        pattern('(Foo)?')->match('')->group(1)->asInt()->keys()->first();
    }

    /**
     * @test
     */
    public function shouldGet_remaining_first(): void
    {
        // when
        $first = pattern('(\d+)')->match('45 65')->remaining(Functions::equals('65'))->group(1)->asInt()->first();

        // then
        $this->assertSame(65, $first);
    }

    /**
     * @test
     */
    public function shouldGet_remaining_first_keys(): void
    {
        // when
        $key = pattern('(\d+)')->match('90 60 75 85')->remaining(Functions::equals('75'))->group(1)->asInt()->keys()->first();

        // then
        $this->assertSame(0, $key);
    }

    /**
     * @test
     */
    public function shouldGet_remaining_all_keys(): void
    {
        // when
        $key = pattern('(\d+)')->match('90 60 75 85')->remaining(Functions::oneOf(['60', '85']))->group(1)->asInt()->keys()->all();

        // then
        $this->assertSame([0, 1], $key);
    }

    /**
     * @test
     */
    public function shouldMapFirst()
    {
        // when
        $letters = pattern('(\d+)')->match('123')->group(1)->asInt()->first(Functions::letters());

        // then
        $this->assertSame(['1', '2', '3'], $letters);
    }

    /**
     * @test
     */
    public function shouldThrow_findFirst_forUnmatchedSubject()
    {
        // given
        $optional = pattern('(Foo)')->match('Bar')->group(1)->asInt()->findFirst(Functions::fail());

        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get group #1 as integer from the first match, but subject was not matched at all');

        // when
        $optional->orThrow();
    }

    /**
     * @test
     */
    public function shouldThrow_findNth_forUnmatchedSubject()
    {
        // given
        $optional = pattern('(Foo)')->match('Bar')->group(1)->asInt()->findNth(0);

        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get group #1 as integer from the 0-nth match, but the subject was not matched at all');

        // when
        $optional->orThrow();
    }

    /**
     * @test
     */
    public function shouldThrow_nth_forInsufficientMatch()
    {
        // given
        $stream = pattern('(\d+)')->match('23 25')->group(1)->asInt();

        // then
        $this->expectException(NoSuchNthElementException::class);
        $this->expectExceptionMessage("Expected to get group #1 as integer from the 2-nth match, but only 2 occurrences are available");

        // when
        $stream->nth(2);
    }

    /**
     * @test
     */
    public function shouldThrow_findNth_forInsufficientMatch()
    {
        // given
        $optional = pattern('(\d+)')->match('23 25')->group(1)->asInt()->findNth(2);

        // then
        $this->expectException(NoSuchNthElementException::class);
        $this->expectExceptionMessage("Expected to get group #1 as integer from the 2-nth match, but only 2 occurrences are available");

        // when
        $optional->orThrow();
    }
}

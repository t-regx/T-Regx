<?php
namespace Test\Feature\TRegx\CleanRegex\Match\group\asInt;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsSameMatches;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;

class MatchGroupIntStreamTest extends TestCase
{
    use AssertsSameMatches;

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
        $this->expectExceptionMessage("Expected to get group #1 from the first match, but subject was not matched at all");

        // when
        pattern('(Foo)')->match('Bar')->group(1)->asInt()->first();
    }

    /**
     * @test
     */
    public function shouldThrow_first_keys_forUnmatchedSubject()
    {
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group #1 from the first match, but subject was not matched at all");

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
        $this->expectExceptionMessage("Expected to parse group #1, but '1  ' is not a valid integer");

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
        $this->expectExceptionMessage("Expected to parse group #1, but '' is not a valid integer");

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
        $this->expectExceptionMessage("Expected to parse group #0, but 'Foo' is not a valid integer");

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
        $this->expectExceptionMessage("Expected to parse group #0, but 'Foo' is not a valid integer");

        // when
        pattern('Foo')->match('Foo')->group(0)->asInt()->keys()->first();
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
    public function shouldThrow_first_forUnmatchedGroup()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group #1 from the first match, but the group was not matched");

        // when
        pattern('(Foo)?')->match('')->group(1)->asInt()->first();
    }

    /**
     * @test
     */
    public function shouldThrow_first_keys_forUnmatchedGroup()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group #1 from the first match, but the group was not matched");

        // when
        pattern('(Foo)?')->match('')->group(1)->asInt()->keys()->first();
    }

    /**
     * @test
     */
    public function shouldGet_ignoring_first(): void
    {
        // when
        $first = pattern('(\d+)')->match('45 65')->ignoring(Functions::equals('65'))->group(1)->asInt()->first();

        // then
        $this->assertSame(65, $first);
    }

    /**
     * @test
     */
    public function shouldGet_ignoring_first_keys(): void
    {
        // when
        $key = pattern('(\d+)')->match('90 60 75 85')->ignoring(Functions::equals('75'))->group(1)->asInt()->keys()->first();

        // then
        $this->assertSame(2, $key);
    }
}

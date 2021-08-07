<?php
namespace Test\Feature\TRegx\CleanRegex\Match\group\offsets;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsSameMatches;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;

/**
 * @coversNothing
 */
class MatchGroupOffsetStreamTest extends TestCase
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
        $this->expectExceptionMessage("Expected to get group #1 offset from the first match, but subject was not matched at all");

        // when
        pattern('(Foo)')->match('Bar')->group(1)->offsets()->first();
    }

    /**
     * @test
     */
    public function shouldThrow_first_keys_forUnmatchedSubject()
    {
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group #1 offset from the first match, but subject was not matched at all");

        // when
        pattern('(Foo)')->match('Bar')->group(1)->offsets()->keys()->first();
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
        pattern('Foo')->match('Foo')->group('missing')->offsets()->all();
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
        pattern('Foo')->match('Foo')->group('missing')->offsets()->first();
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
        pattern('Foo')->match('Foo')->group('missing')->offsets()->keys()->first();
    }

    /**
     * @test
     */
    public function shouldGet_all_forUnmatchedGroup()
    {
        // when
        $groups = pattern('"(\d+)?"')->match('"12" "" "13"')->group(1)->offsets()->all();

        // then
        $this->assertSame([1, null, 9], $groups);
    }

    /**
     * @test
     */
    public function shouldThrow_first_forUnmatchedGroup()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group #1 offset from the first match, but the group was not matched");

        // when
        pattern('(Foo)?')->match('')->group(1)->offsets()->first();
    }

    /**
     * @test
     */
    public function shouldThrow_first_keys_forUnmatchedGroup()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group #1 offset from the first match, but the group was not matched");

        // when
        pattern('(Foo)?')->match('')->group(1)->offsets()->keys()->first();
    }

    /**
     * @test
     */
    public function shouldGet_remaining_first(): void
    {
        // when
        $first = pattern('(\d+)')->match('Foo 45 65')->remaining(Functions::equals('65'))->group(1)->offsets()->first();

        // then
        $this->assertSame(7, $first);
    }

    /**
     * @test
     */
    public function shouldGet_remaining_first_keys(): void
    {
        // when
        $key = pattern('(\d+)')->match('90 60 75 85')->remaining(Functions::equals('75'))->group(1)->offsets()->keys()->first();

        // then
        $this->assertSame(0, $key);
    }

    /**
     * @test
     */
    public function shouldGet_remaining_all_keys(): void
    {
        // when
        $key = pattern('(\d+)')->match('90 60 75 85')->remaining(Functions::oneOf(['60', '85']))->group(1)->offsets()->keys()->all();

        // then
        $this->assertSame([0, 1], $key);
    }
}

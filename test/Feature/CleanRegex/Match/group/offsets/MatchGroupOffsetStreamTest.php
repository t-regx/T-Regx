<?php
namespace Test\Feature\TRegx\CleanRegex\Match\group\offsets;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsSameMatches;
use Test\Utils\ExactExceptionMessage;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Exception\NoSuchStreamElementException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;

/**
 * @coversNothing
 */
class MatchGroupOffsetStreamTest extends TestCase
{
    use AssertsSameMatches, ExactExceptionMessage;

    /**
     * @test
     */
    public function shouldThrow_first_forUnmatchedSubject()
    {
        // given
        $optional = pattern('(Foo)')->match('Bar')->group(1)->offsets()->findFirst(Functions::fail());

        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get group #1 offset from the first match, but subject was not matched at all');

        // when
        $optional->orThrow();
    }

    /**
     * @test
     */
    public function shouldThrow_first_keys_forUnmatchedSubject()
    {
        // given
        $optional = pattern('(Foo)')->match('Bar')->group(1)->offsets()->keys()->findFirst(Functions::fail());

        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get group #1 offset from the first match, but subject was not matched at all');

        // when
        $optional->orThrow();
    }

    /**
     * @test
     */
    public function shouldThrow_all_forNonexistentGroup()
    {
        // given
        $offsets = pattern('Foo')->match('Foo')->group('missing')->offsets();

        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        $offsets->all();
    }

    /**
     * @test
     */
    public function shouldThrow_all_forNonexistentGroup_onUnmatchedSubject()
    {
        // given
        $offsets = pattern('Foo')->match('Bar')->group('missing')->offsets();

        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        $offsets->all();
    }

    /**
     * @test
     */
    public function shouldThrow_first_forNonexistentGroup()
    {
        // given
        $offsets = pattern('Foo')->match('Foo')->group('missing')->offsets();

        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        $offsets->first();
    }

    /**
     * @test
     */
    public function shouldThrow_first_forNonexistentGroup_onUnmatchedSubject()
    {
        // given
        $offsets = pattern('Foo')->match('Bar')->group('missing')->offsets();

        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        $offsets->first();
    }

    /**
     * @test
     */
    public function shouldThrow_first_keys_forNonexistentGroup()
    {
        // when
        $keys = pattern('Foo')->match('Foo')->group('missing')->offsets()->keys();

        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        $keys->first();
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
        // given
        $optional = pattern('(Foo)?')->match('')->group(1)->offsets()->findFirst(Functions::identity());

        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group #1 offset from the first match, but the group was not matched");

        // when
        $optional->orThrow();
    }

    /**
     * @test
     */
    public function shouldThrow_forEmptyStream()
    {
        // given
        $optional = pattern('Foo')
            ->match('Bar')
            ->group(0)
            ->offsets()
            ->distinct()
            ->filter(Functions::fail())
            ->groupByCallback(Functions::fail())
            ->findFirst(Functions::fail());

        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get group #0 offset from the first match, but subject was not matched at all');

        // when
        $optional->orThrow();
    }

    /**
     * @test
     */
    public function shouldThrow_first_keys_forUnmatchedGroup()
    {
        // given
        $optional = pattern('(Foo)?')->match('')->group(1)->offsets()->keys()->findFirst(Functions::identity());

        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage("Expected to get group #1 offset from the first match, but the group was not matched");

        // when
        $optional->orThrow();
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

    /**
     * @test
     */
    public function shouldMapFirst()
    {
        // when
        $result = pattern('(\d+)')->match('foo:123')->group(1)->offsets()->first(Functions::surround('*'));

        // then
        $this->assertSame('*4*', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_findNth_forUnmatchedSubject()
    {
        // given
        $optional = pattern('(Foo)')->match('Bar')->group(1)->offsets()->findNth(0);

        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get group #1 offset from the 0-nth match, but the subject was not matched at all');

        // when
        $optional->orThrow();
    }

    /**
     * @test
     */
    public function shouldThrow_findNth_forInsufficientMatch()
    {
        // given
        $optional = pattern('(Foo)')->match('Foo Foo')->group(1)->offsets()->findNth(2);

        // then
        $this->expectException(NoSuchNthElementException::class);
        $this->expectExceptionMessage('Expected to get group #1 offset from the 2-nth match, but only 2 occurrences are available');

        // when
        $optional->orThrow();
    }
}

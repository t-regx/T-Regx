<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Replace\By;

use PHPUnit\Framework\TestCase;
use Test\Utils\CustomSubjectException;
use Test\Utils\Functions;
use Test\Utils\Impl\ComputedMapper;
use Test\Utils\Impl\NoReplacementMapper;
use Test\Utils\Internal;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Exception\Messages\Group\ReplacementWithUnmatchedGroupMessage;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Replace\By\GroupFallbackReplacer;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\ConstantReturnStrategy;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\ThrowStrategy;
use TRegx\CleanRegex\Internal\Replace\Counting\IgnoreCounting;
use TRegx\CleanRegex\Internal\Subject;

class GroupFallbackReplacerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReplace_usingStrategy()
    {
        // given
        $mapReplacer = $this->create('\[(two|three|four)\]', '[two], [three], [four]');
        $mapper = new ComputedMapper(Functions::singleArg('strToUpper'));

        // when
        $result = $mapReplacer->replaceOrFallback(1, $mapper, new DefaultStrategy());

        // then
        $this->assertSame('TWO, THREE, FOUR', $result);
    }

    /**
     * @test
     */
    public function shouldReplace_emptyString()
    {
        // given
        $fallbackReplacer = $this->create('\[(two|four|)\]', '[two] [] [four]');

        // when
        $result = $fallbackReplacer->replaceOrFallback(1,
            new ComputedMapper(Functions::singleArg('strLen')),
            new DefaultStrategy());

        // then
        $this->assertSame('3 0 4', $result);
    }

    /**
     * @test
     */
    public function shouldFallback_toStrategy_unmatchedGroup()
    {
        // given
        $fallbackReplacer = $this->create('\[(two|four)?\]', '[two] [] [four]');

        // when
        $result = $fallbackReplacer->replaceOrFallback(1, new NoReplacementMapper(), new ConstantReturnStrategy('fallback'));

        // then
        $this->assertSame('[two] fallback [four]', $result);
    }

    /**
     * @test
     */
    public function shouldFallback_toDefault()
    {
        // given
        $fallbackReplacer = $this->create('\[()\]', '');

        // when
        $result = $fallbackReplacer->replaceOrFallback(1, new NoReplacementMapper(), new DefaultStrategy());

        // then
        $this->assertSame('Subject not matched', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidGroup()
    {
        // given
        $fallbackReplacer = $this->create('', '');

        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: #1");

        // when
        $fallbackReplacer->replaceOrFallback(1, new NoReplacementMapper(), new DefaultStrategy());
    }

    /**
     * @test
     */
    public function shouldThrow_forUnmatchedGroup_last()
    {
        // given
        $fallbackReplacer = $this->create('word:(\d)?', 'word:');

        // then
        $this->expectException(CustomSubjectException::class);
        $this->expectExceptionMessage("Expected to replace with group #1, but the group was not matched");

        // when
        $fallbackReplacer->replaceOrFallback(
            1,
            new NoReplacementMapper(),
            new ThrowStrategy(CustomSubjectException::class, new ReplacementWithUnmatchedGroupMessage(1))
        );
    }

    /**
     * @test
     */
    public function shouldThrow_forUnmatchedGroup_middle()
    {
        // given
        $fallbackReplacer = $this->create('foo:(\d)?:(bar)', 'foo::bar');

        // then
        $this->expectException(CustomSubjectException::class);
        $this->expectExceptionMessage("Expected to replace with group #1, but the group was not matched");

        // when
        $fallbackReplacer->replaceOrFallback(
            1,
            new NoReplacementMapper(),
            new ThrowStrategy(CustomSubjectException::class, new ReplacementWithUnmatchedGroupMessage(1))
        );
    }

    public function create($pattern, $subject): GroupFallbackReplacer
    {
        return new GroupFallbackReplacer(
            Internal::pattern($pattern),
            new Subject($subject),
            -1,
            new ConstantReturnStrategy('Subject not matched'),
            new IgnoreCounting(),
            new ApiBase(Internal::pattern($pattern), $subject, new UserData())
        );
    }
}

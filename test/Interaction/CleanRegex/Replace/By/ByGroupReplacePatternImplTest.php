<?php
namespace Test\Interaction\TRegx\CleanRegex\Replace\By;

use PHPUnit\Framework\TestCase;
use Test\Utils\CustomSubjectException;
use Test\Utils\Functions;
use Test\Utils\Internal;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Replace\By\GroupFallbackReplacer;
use TRegx\CleanRegex\Internal\Replace\By\IdentityWrapper;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\LazyMessageThrowStrategy;
use TRegx\CleanRegex\Internal\Replace\By\PerformanceEmptyGroupReplace;
use TRegx\CleanRegex\Internal\Replace\Counting\IgnoreCounting;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Replace\By\ByGroupReplacePatternImpl;
use TRegx\CleanRegex\Replace\Callback\ReplacePatternCallbackInvoker;

class ByGroupReplacePatternImplTest extends TestCase
{
    /**
     * @test
     */
    public function shouldOrElse()
    {
        // given
        $byReplacePattern = $this->create('word(\d+)?', 'word');

        // when
        $result = $byReplacePattern->orElseWith('failing');

        // then
        $this->assertSame('failing', $result);
    }

    /**
     * @test
     */
    public function shouldOrReturn()
    {
        // given
        $byReplacePattern = $this->create('word(\d+)?', 'word');

        // when
        $result = $byReplacePattern->orElseCalling(Functions::constant('called'));

        // then
        $this->assertSame('called', $result);
    }

    /**
     * @test
     */
    public function shouldOrThrow()
    {
        // given
        $byReplacePattern = $this->create('word(\d+)?', 'word');

        // then
        $this->expectException(CustomSubjectException::class);
        $this->expectExceptionMessage("Expected to replace with group #1, but the group was not matched");

        // when
        $byReplacePattern->orElseThrow(CustomSubjectException::class);
    }

    public function create(string $pattern, string $subject): ByGroupReplacePatternImpl
    {
        $internalPattern = Internal::pattern($pattern);
        $subjectable = new Subject($subject);

        return new ByGroupReplacePatternImpl(
            new GroupFallbackReplacer(
                $internalPattern,
                $subjectable,
                -1,
                new DefaultStrategy(),
                new IgnoreCounting(),
                new ApiBase($internalPattern, $subject, new UserData())),
            new PerformanceEmptyGroupReplace($internalPattern, $subjectable, -1),
            new ReplacePatternCallbackInvoker($internalPattern, $subjectable, -1, new LazyMessageThrowStrategy(\AssertionError::class), new IgnoreCounting()),
            1,
            $subject,
            new IdentityWrapper());
    }
}

<?php
namespace Test\Interaction\TRegx\CleanRegex\Replace\By;

use PHPUnit\Framework\TestCase;
use Test\Utils\CustomSubjectException;
use Test\Utils\Definitions;
use Test\Utils\Functions;
use TRegx\CleanRegex\Internal\GroupKey\GroupIndex;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Replace\By\GroupFallbackReplacer;
use TRegx\CleanRegex\Internal\Replace\By\IdentityWrapper;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\LazyMessageThrowStrategy;
use TRegx\CleanRegex\Internal\Replace\By\PerformanceEmptyGroupReplace;
use TRegx\CleanRegex\Internal\Replace\Counting\IgnoreCounting;
use TRegx\CleanRegex\Internal\StringSubject;
use TRegx\CleanRegex\Replace\By\ByGroupReplacePattern;
use TRegx\CleanRegex\Replace\Callback\ReplacePatternCallbackInvoker;

/**
 * @covers \TRegx\CleanRegex\Replace\By\ByGroupReplacePattern
 */
class ByGroupReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldOrElse()
    {
        // given
        $byReplacePattern = $this->byGroup('word(\d+)?', 'word');

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
        $byReplacePattern = $this->byGroup('word(\d+)?', 'word');

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
        $byReplacePattern = $this->byGroup('word(\d+)?', 'word');

        // then
        $this->expectException(CustomSubjectException::class);
        $this->expectExceptionMessage("Expected to replace with group #1, but the group was not matched");

        // when
        $byReplacePattern->orElseThrow(CustomSubjectException::class);
    }

    public function byGroup(string $pattern, string $stringSubject): ByGroupReplacePattern
    {
        $definition = Definitions::pattern($pattern);
        $subject = new StringSubject($stringSubject);

        return new ByGroupReplacePattern(
            new GroupFallbackReplacer(
                $definition,
                $subject,
                -1,
                new DefaultStrategy(),
                new IgnoreCounting(),
                new ApiBase($definition, $subject, new UserData())),
            new PerformanceEmptyGroupReplace($definition, $subject, -1),
            new ReplacePatternCallbackInvoker($definition, $subject, -1, new LazyMessageThrowStrategy(\AssertionError::class), new IgnoreCounting()),
            new GroupIndex(1),
            $subject,
            new IdentityWrapper());
    }
}

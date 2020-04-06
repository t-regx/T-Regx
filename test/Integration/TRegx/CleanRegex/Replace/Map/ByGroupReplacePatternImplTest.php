<?php
namespace Test\Integration\TRegx\CleanRegex\Replace\Map;

use PHPUnit\Framework\TestCase;
use Test\Utils\CustomSubjectException;
use Test\Utils\Functions;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Replace\By\ByGroupReplacePatternImpl;
use TRegx\CleanRegex\Replace\By\GroupFallbackReplacer;
use TRegx\CleanRegex\Replace\By\PerformanceEmptyGroupReplace;
use TRegx\CleanRegex\Replace\Callback\ReplacePatternCallbackInvoker;
use TRegx\CleanRegex\Replace\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\LazyMessageThrowStrategy;

class ByReplacePatternImplTest extends TestCase
{
    /**
     * @test
     */
    public function shouldOrElse()
    {
        // given
        $byReplacePattern = $this->create('word(\d+)?', 'word');

        // when
        $result = $byReplacePattern->orReturn('failing');

        // then
        $this->assertEquals('failing', $result);
    }

    /**
     * @test
     */
    public function shouldOrReturn()
    {
        // given
        $byReplacePattern = $this->create('word(\d+)?', 'word');

        // when
        $result = $byReplacePattern->orElse(Functions::constant('failing'));

        // then
        $this->assertEquals('failing', $result);
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
        $this->expectExceptionMessage("Expected to replace with group '1', but the group was not matched");

        // when
        $byReplacePattern->orThrow(CustomSubjectException::class);
    }

    public function create(string $pattern, string $subject): ByGroupReplacePatternImpl
    {
        $internalPattern = InternalPattern::standard($pattern);
        $subjectable = new Subject($subject);

        return new ByGroupReplacePatternImpl(
            new GroupFallbackReplacer(
                $internalPattern,
                $subjectable,
                -1,
                new DefaultStrategy(),
                new ApiBase($internalPattern, $subject, new UserData())
            ),
            new PerformanceEmptyGroupReplace($internalPattern, $subjectable, -1),
            new ReplacePatternCallbackInvoker($internalPattern, $subjectable, -1, new LazyMessageThrowStrategy(\AssertionError::class)),
            1,
            $subject
        );
    }
}

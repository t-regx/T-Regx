<?php
namespace Test\Integration\TRegx\CleanRegex\Replace\Map;

use PHPUnit\Framework\TestCase;
use Test\Feature\TRegx\CleanRegex\Replace\by\group\CustomException;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\SubjectableImpl;
use TRegx\CleanRegex\Replace\By\ByGroupReplacePatternImpl;
use TRegx\CleanRegex\Replace\By\GroupFallbackReplacer;
use TRegx\CleanRegex\Replace\NonReplaced\DefaultStrategy;

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
        $result = $byReplacePattern->orElse(function () {
            return 'failing';
        });

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
        $this->expectException(CustomException::class);
        $this->expectExceptionMessage("Expected to replace with group '1', but the group was not matched");

        // when
        $byReplacePattern->orThrow(CustomException::class);
    }

    public function create(string $pattern, string $subject): ByGroupReplacePatternImpl
    {
        return new ByGroupReplacePatternImpl(
            new GroupFallbackReplacer(
                new InternalPattern($pattern),
                new SubjectableImpl($subject),
                -1,
                new DefaultStrategy(),
                new ApiBase(new InternalPattern($pattern), $subject, new UserData())
            ),
            1,
            $subject
        );
    }
}

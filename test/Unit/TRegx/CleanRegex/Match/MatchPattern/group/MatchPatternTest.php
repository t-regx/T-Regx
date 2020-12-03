<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\group;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Match\MatchPattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrow_onInvalidGroupName()
    {
        // given
        $pattern = new MatchPattern(InternalPattern::pcre('//'), '');

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Group index must be an integer or a string, given: array (0)'); // TODO copy paste

        // when
        $pattern->group([]);
    }
}

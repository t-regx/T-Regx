<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\group\offsets\first;

use PHPUnit\Framework\TestCase;
use Test\Utils\Internal;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Match\MatchPattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrow_onNotMatchedGroup()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('(?<unmatched>not this time)? (?<existing>[a-z]+)'), ' matching');

        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group 'unmatched' from the first match, but the group was not matched");

        // when
        $pattern->group('unmatched')->offsets()->first();
    }
}

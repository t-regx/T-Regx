<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\Details\group;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\GroupNotMatchedException;
use TRegx\CleanRegex\Match\Details\ReplaceMatch;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReplaceWithGroup_matched()
    {
        // given
        $pattern = 'http://(?<name>[a-z]+)\.(?<domain>com|org)?';
        $subject = 'Links: http://google.com';

        // when
        $result = pattern($pattern)
            ->replace($subject)
            ->first()
            ->callback(function (ReplaceMatch $match) {
                // then
                return $match->group('domain');
            });

        // then
        $this->assertEquals('Links: com', $result);
    }

    /**
     * @test
     */
    public function shouldReplaceWithGroup_notMatched_index()
    {
        // given
        $pattern = 'http://(?<name>[a-z]+)(?<domain>(?:\.com|org))?';
        $subject = 'Links: http://google';

        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to replace with group '2', but the group was not matched");

        // when
        pattern($pattern)
            ->replace($subject)
            ->first()
            ->callback(function (ReplaceMatch $match) {
                // then
                return $match->group(2);
            });
    }

    /**
     * @test
     */
    public function shouldReplaceWithGroup_notMatched_name()
    {
        // given
        $pattern = 'http://(?<name>[a-z]+)(?<domain>(?:\.com|org))?';
        $subject = 'Links: http://google';

        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to replace with group 'domain', but the group was not matched");

        // when
        pattern($pattern)
            ->replace($subject)
            ->first()
            ->callback(function (ReplaceMatch $match) {
                // then
                return $match->group('domain');
            });
    }
}

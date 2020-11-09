<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\Details\get;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Match\Details\ReplaceDetail;

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
        $result = pattern($pattern)->replace($subject)->first()->callback(function (ReplaceDetail $match) {
            // then
            return $match->get('domain');
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
        $this->expectExceptionMessage("Expected to get group '2', but the group was not matched");

        // when
        pattern($pattern)->replace($subject)->first()->callback(function (ReplaceDetail $match) {
            // then
            return $match->get(2);
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
        $this->expectExceptionMessage("Expected to get group 'domain', but the group was not matched");

        // when
        pattern($pattern)
            ->replace($subject)
            ->first()
            ->callback(function (ReplaceDetail $match) {
                // then
                return $match->get('domain');
            });
    }
}

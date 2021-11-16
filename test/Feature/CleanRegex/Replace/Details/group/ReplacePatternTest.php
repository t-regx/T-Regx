<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\Details\group;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Replace\Details\ReplaceDetail;

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
        $result = pattern($pattern)->replace($subject)->first()->callback(function (ReplaceDetail $detail) {
            // then
            return $detail->group('domain');
        });

        // then
        $this->assertSame('Links: com', $result);
    }

    /**
     * @test
     */
    public function shouldReplaceWithGroup_duplicate_matched()
    {
        // given
        $pattern = 'http://(?<name>[a-z]+)\.(?<domain>com|org)?';
        $subject = 'Links: http://google.com';

        // when
        $result = pattern($pattern)->replace($subject)->first()->callback(function (ReplaceDetail $detail) {
            // then
            return $detail->usingDuplicateName()->group('domain');
        });

        // then
        $this->assertSame('Links: com', $result);
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
        $this->expectExceptionMessage("Expected to replace with group #2, but the group was not matched");

        // when
        pattern($pattern)->replace($subject)->first()->callback(function (ReplaceDetail $detail) {
            // then
            return $detail->group(2);
        });
    }

    /**
     * @test
     */
    public function shouldReplaceWithGroup_duplicate_notMatched_name()
    {
        // given
        $pattern = 'http://(?<name>[a-z]+)(?<domain>(?:\.com|org))?';
        $subject = 'Links: http://google';

        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to replace with group 'domain', but the group was not matched");

        // when
        pattern($pattern)->replace($subject)->first()->callback(function (ReplaceDetail $detail) {
            // then
            return $detail->usingDuplicateName()->group('domain');
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
        pattern($pattern)->replace($subject)->first()->callback(function (ReplaceDetail $detail) {
            // then
            return $detail->group('domain');
        });
    }
}

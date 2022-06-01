<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\Details\group;

use PHPUnit\Framework\TestCase;
use Test\Utils\DetailFunctions;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Pattern;
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

    /**
     * @test
     */
    public function shouldNotGet_modifiedSubject()
    {
        // given
        Pattern::of('Foo(Bar)?')->replace('Foo')->callback(DetailFunctions::outGroup(1, $group, ''));
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call modifiedSubject() for group #1, but the group was not matched");
        // when
        $group->modifiedSubject();
    }

    /**
     * @test
     */
    public function shouldNotGet_modifiedOffset()
    {
        // given
        Pattern::of('Foo(?<second>Bar)?')->replace('Foo')->callback(DetailFunctions::outGroup('second', $group, ''));
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call modifiedOffset() for group 'second', but the group was not matched");
        // when
        $group->modifiedOffset();
    }

    /**
     * @test
     */
    public function shouldNotGet_byteModifiedOffset()
    {
        // given
        Pattern::of('Foo(?<bar>Bar)?')->replace('Foo')->callback(DetailFunctions::outGroup('bar', $group, ''));
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call byteModifiedOffset() for group 'bar', but the group was not matched");
        // when
        $group->byteModifiedOffset();
    }
}

<?php
namespace Test\Unit\TRegx\CleanRegex\Match\Details\Group;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Match\Details\Group\NameOnlyDetails;
use Test\Fakes\CleanRegex\Internal\ThrowSubject;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\Factory\Optional\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Replace\Details\Group\ReplaceNotMatchedGroup;

/**
 * @covers \TRegx\CleanRegex\Replace\Details\Group\ReplaceNotMatchedGroup
 */
class ReplaceNotMatchedGroupTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotGet_modifiedSubject()
    {
        // given
        $matchGroup = new ReplaceNotMatchedGroup(new ThrowSubject(), new NameOnlyDetails('first'), $this->worker());

        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call modifiedSubject() for group 'first', but the group was not matched");

        // when
        $matchGroup->modifiedSubject();
    }

    /**
     * @test
     */
    public function shouldNotGet_modifiedOffset()
    {
        // given
        $matchGroup = new ReplaceNotMatchedGroup(new ThrowSubject(), new NameOnlyDetails('second'), $this->worker());

        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call modifiedOffset() for group 'second', but the group was not matched");

        // when
        $matchGroup->modifiedOffset();
    }

    /**
     * @test
     */
    public function shouldNotGet_byteModifiedOffset()
    {
        // given
        $matchGroup = new ReplaceNotMatchedGroup(new ThrowSubject(), new NameOnlyDetails('bar'), $this->worker());

        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call byteModifiedOffset() for group 'bar', but the group was not matched");

        // when
        $matchGroup->byteModifiedOffset();
    }

    private function worker(): NotMatchedOptionalWorker
    {
        return $this->createMock(NotMatchedOptionalWorker::class);
    }
}

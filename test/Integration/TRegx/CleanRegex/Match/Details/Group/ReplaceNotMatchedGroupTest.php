<?php
namespace Test\Integration\TRegx\CleanRegex\Match\Details\Group;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\Factory\GroupExceptionFactory;
use TRegx\CleanRegex\Internal\Factory\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupDetails;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Details\Group\ReplaceMatchGroup;
use TRegx\CleanRegex\Match\Details\Group\ReplaceNotMatchedGroup;

class ReplaceNotMatchedGroupTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotGet_modifiedOffset()
    {
        // given
        $matchGroup = $this->matchGroup('first');

        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call modifiedOffset() for group 'first', but group was not matched");

        // when
        $matchGroup->modifiedOffset();
    }

    private function matchGroup(string $group): ReplaceMatchGroup
    {
        /** @var Subjectable $subject */
        /** @var GroupDetails $groupDetails */
        /** @var NotMatchedOptionalWorker $worker */
        $subject = $this->createMock(Subjectable::class);
        $groupDetails = $this->createMock(GroupDetails::class);
        $worker = $this->createMock(NotMatchedOptionalWorker::class);
        return new ReplaceNotMatchedGroup($groupDetails, new GroupExceptionFactory($subject, $group), $worker);
    }
}

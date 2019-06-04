<?php
namespace Test\Integration\TRegx\CleanRegex\Match\Details\Group;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\CleanRegex\Messages\Group\GroupMessage;
use TRegx\CleanRegex\Internal\Factory\GroupExceptionFactory;
use TRegx\CleanRegex\Internal\Factory\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupDetails;
use TRegx\CleanRegex\Internal\MatchAllResults;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatches;
use TRegx\CleanRegex\Internal\SubjectableImpl;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;
use TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup;
use TRegx\CleanRegex\Match\Details\NotMatched;

class NotMatchedGroupTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetText()
    {
        // given
        $matchGroup = $this->matchGroup();

        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call text() for group 'first', but group was not matched");

        // when
        $matchGroup->text();
    }

    /**
     * @test
     */
    public function shouldMatch()
    {
        // given
        $matchGroup = $this->matchGroup();

        // when
        $matches = $matchGroup->matched();

        // then
        $this->assertFalse($matches);
    }

    /**
     * @test
     */
    public function shouldGetOffset()
    {
        // given
        $matchGroup = $this->matchGroup();

        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call offset() for group 'first', but group was not matched");

        // when
        $matchGroup->offset();
    }

    /**
     * @test
     */
    public function shouldGetByteOffset()
    {
        // given
        $matchGroup = $this->matchGroup();

        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call byteOffset() for group 'first', but group was not matched");

        // when
        $matchGroup->byteOffset();
    }

    /**
     * @test
     */
    public function shouldGetName()
    {
        // given
        $matchGroup = $this->matchGroup();

        // when
        $name = $matchGroup->name();

        // then
        $this->assertEquals('first', $name);
    }

    /**
     * @test
     */
    public function shouldGetIndex()
    {
        // given
        $matchGroup = $this->matchGroup();

        // when
        $index = $matchGroup->index();

        // then
        $this->assertEquals(1, $index);
    }

    /**
     * @test
     */
    public function shouldControlMatched_orThrow()
    {
        // given
        $matchGroup = $this->matchGroup();

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Expected to get group 'first', but it was not matched");

        // when
        $matchGroup->orThrow(InvalidArgumentException::class);
    }

    /**
     * @test
     */
    public function shouldControlMatched_orElse()
    {
        // given
        $matchGroup = $this->matchGroup();

        // when
        $orElse = $matchGroup->orElse(function (NotMatched $notMatched) {
            return $notMatched->subject();
        });

        // then
        $this->assertEquals('My super subject', $orElse);
    }

    /**
     * @test
     */
    public function shouldControlMatched_orReturn()
    {
        // given
        $matchGroup = $this->matchGroup();

        // when
        $orReturn = $matchGroup->orReturn(13);

        // then
        $this->assertEquals(13, $orReturn);
    }

    private function matchGroup(): MatchGroup
    {
        $subject = new SubjectableImpl('My super subject');
        return new NotMatchedGroup(
            new GroupDetails('first', 1, 'first', new MatchAllResults(new RawMatches([]), 'first')),
            new GroupExceptionFactory($subject, 'first'),
            new NotMatchedOptionalWorker(
                new GroupMessage('first'),
                $subject,
                new NotMatched(new RawMatches([]), $subject)
            )
        );
    }
}

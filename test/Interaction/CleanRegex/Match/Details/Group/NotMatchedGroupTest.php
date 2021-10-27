<?php
namespace Test\Interaction\TRegx\CleanRegex\Match\Details\Group;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\ThrowSubject;
use Test\Utils\CustomSubjectException;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\GroupKey\GroupName;
use TRegx\CleanRegex\Internal\GroupKey\GroupSignature;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupDetails;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Model\Match\RawMatches;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
use TRegx\CleanRegex\Internal\StringSubject;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup;
use TRegx\CleanRegex\Match\Details\NotMatched;

/**
 * @covers \TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup
 */
class NotMatchedGroupTest extends TestCase
{
    /**
     * @test
     * @dataProvider optionalMethods
     * @param string $method
     * @param array $arguments
     * @param $expected
     */
    public function testMethodOptional(string $method, array $arguments, $expected)
    {
        // given
        $matchGroup = $this->matchGroup();

        // when
        $matches = $matchGroup->$method(...$arguments);

        // then
        $this->assertSame($expected, $matches);
    }

    public function optionalMethods(): array
    {
        return [
            ['matched', [], false],
            ['equals', ['any'], false],
            ['name', [], 'first'],
            ['index', [], 1],
            ['orElse', [Functions::constant('result')], 'result'],
            ['orReturn', [13], 13],
        ];
    }

    /**
     * @test
     * @dataProvider nonOptionalMethods
     * @param string $method
     * @param array $arguments
     */
    public function testMethodNonOptional(string $method, array $arguments = [])
    {
        // given
        $matchGroup = $this->matchGroup();

        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call $method() for group 'first', but the group was not matched");

        // when
        $matchGroup->$method(...$arguments);
    }

    public function nonOptionalMethods(): array
    {
        return [
            ['text', ['']],
            ['textLength', ['']],
            ['textByteLength', ['']],
            ['substitute', ['']],
            ['offset'],
            ['byteOffset'],
            ['tail'],
            ['byteTail'],
        ];
    }

    /**
     * @test
     */
    public function shouldControlMatched_orThrow()
    {
        // given
        $matchGroup = $this->matchGroup(new StringSubject('subject'));

        try {
            // when
            $matchGroup->orThrow(CustomSubjectException::class);
        } catch (CustomSubjectException $exception) {
            // then
            $this->assertSame("Expected to get group 'first', but it was not matched", $exception->getMessage());
            $this->assertSame('subject', $exception->subject);
        }
    }

    private function matchGroup(Subject $subject = null): NotMatchedGroup
    {
        return new NotMatchedGroup(
            $subject ?? new ThrowSubject(),
            new GroupDetails(new GroupSignature(1, 'first'), new GroupName('first'), new EagerMatchAllFactory(new RawMatchesOffset([]))),
            new NotMatched(new RawMatches([]), new ThrowSubject()));
    }
}

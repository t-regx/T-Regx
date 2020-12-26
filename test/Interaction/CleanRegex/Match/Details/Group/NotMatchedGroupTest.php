<?php
namespace Test\Interaction\TRegx\CleanRegex\Match\Details\Group;

use PHPUnit\Framework\TestCase;
use Test\Utils\CustomSubjectException;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\Exception\Messages\Group\GroupMessage;
use TRegx\CleanRegex\Internal\Factory\GroupExceptionFactory;
use TRegx\CleanRegex\Internal\Factory\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupDetails;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatches;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup;
use TRegx\CleanRegex\Match\Details\NotMatched;

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
            ['orElse', [function (NotMatched $notMatched) {
                return $notMatched->subject();
            }], 'My super subject'],
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
        $matchGroup = $this->matchGroup();

        // then
        $this->expectException(CustomSubjectException::class);
        $this->expectExceptionMessage("Expected to get group 'first', but it was not matched");

        // when
        $matchGroup->orThrow(CustomSubjectException::class);
    }

    private function matchGroup(): NotMatchedGroup
    {
        $subject = new Subject('My super subject');
        return new NotMatchedGroup(
            new GroupDetails('first', 1, 'first', new EagerMatchAllFactory(new RawMatchesOffset([]))),
            new GroupExceptionFactory($subject, 'first'),
            new NotMatchedOptionalWorker(
                new GroupMessage('first'),
                $subject,
                new NotMatched(new RawMatches([]), $subject)
            ),
            '$unused'
        );
    }
}

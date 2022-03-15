<?php
namespace Test\Interaction\TRegx\CleanRegex\Match\Details;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\ThrowSubject;
use Test\Utils\PhpunitPolyfill;
use TRegx\CleanRegex\Internal\Model\Match\RawMatches;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\NotMatched;

/**
 * @covers \TRegx\CleanRegex\Match\Details\NotMatched
 */
class NotMatchedTest extends TestCase
{
    use PhpunitPolyfill;

    /**
     * @test
     */
    public function shouldNotCastToString()
    {
        // pre
        $previous = error_reporting(E_ALL);

        // given
        $notMatched = new NotMatched(new RawMatches([]), new Subject('subject'));

        // then
        if (PHP_VERSION_ID < 70400) {
            $this->expectError();
        } else {
            $this->expectException(\Error::class);
        }
        $this->expectExceptionMessage('Object of class TRegx\CleanRegex\Match\Details\NotMatched could not be converted to string');

        // when
        /**
         * @noinspection PhpToStringImplementationInspection
         * Obviously method __toString is not implemented, because the tests it is not.
         */
        $string = (string)$notMatched;

        // post
        error_reporting($previous);
    }

    /**
     * @test
     */
    public function shouldGet_subject()
    {
        //
        $notMatched = new NotMatched(new RawMatches([]), new Subject('subject'));

        // when
        $subject = $notMatched->subject();

        // then
        $this->assertSame('subject', $subject);
    }

    /**
     * @test
     * @dataProvider existingGroups
     * @param string|int $group
     */
    public function shouldHasGroup($group)
    {
        // given
        $notMatched = $this->createNotMatched();

        // when
        $hasGroup = $notMatched->hasGroup($group);

        // then
        $this->assertTrue($hasGroup, "Failed asserting that group $group exists");
    }

    /**
     * @test
     * @dataProvider missingGroups
     * @param string|int $groupIdentifier
     */
    public function shouldHasGroup_not($groupIdentifier)
    {
        // given
        $notMatched = $this->createNotMatched();

        // when
        $hasGroup = $notMatched->hasGroup($groupIdentifier);

        // then
        $this->assertFalse($hasGroup, "Failed asserting that group $groupIdentifier is missing");
    }

    public function existingGroups(): array
    {
        return [
            [0],
            [1],
            [2],
            ['group'],
            ['xd'],
        ];
    }

    public function missingGroups(): array
    {
        return [
            [3, false],
            [4, false],
            [5, false],
            ['missing', false],
        ];
    }

    /**
     * @test
     * @dataProvider invalidGroups
     * @param string|int $groupIdentifier
     * @param string $message
     */
    public function shouldThrow_invalidGroupName($groupIdentifier, string $message)
    {
        // given
        $notMatched = new NotMatched(new RawMatches([]), new ThrowSubject());

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        // when
        $notMatched->hasGroup($groupIdentifier);
    }

    public function invalidGroups(): array
    {
        return [
            [-1, 'Group index must be a non-negative integer, but -1 given'],
            [-3, 'Group index must be a non-negative integer, but -3 given'],
            ['2startingWithDigit', "Group name must be an alphanumeric string, not starting with a digit, but '2startingWithDigit' given"],
            ['dashed-dashed', "Group name must be an alphanumeric string, not starting with a digit, but 'dashed-dashed' given"]
        ];
    }

    /**
     * @test
     */
    public function shouldGet_groupNames()
    {
        // given
        $notMatched = $this->createNotMatched();

        // when
        $groupNames = $notMatched->groupNames();

        // then
        $this->assertSame(['group', 'xd'], $groupNames);
    }

    /**
     * @test
     */
    public function shouldGet_groupNames_jagged()
    {
        // given
        $notMatched = $this->createNotMatched_jagged();

        // when
        $groupNames = $notMatched->groupNames();

        // then
        $this->assertSame(['group', null, 'xd', null], $groupNames);
    }

    private function createNotMatched(): NotMatched
    {
        $matches = [
            0       => [],
            'group' => [],
            1       => [],
            'xd'    => [],
            2       => [],
        ];
        return new NotMatched(new RawMatches($matches), new Subject('subject'));
    }

    /**
     * @test
     */
    public function shouldGet_groupsCount()
    {
        // given
        $notMatched = $this->createNotMatched_jagged();

        // when
        $groupsCount = $notMatched->groupsCount();

        // then
        $this->assertSame(4, $groupsCount);
    }

    private function createNotMatched_jagged(): NotMatched
    {
        $matches = [
            0       => [],
            'group' => [],
            1       => [],
            2       => [],
            'xd'    => [],
            3       => [],
            4       => [],
        ];
        return new NotMatched(new RawMatches($matches), new Subject('subject'));
    }
}

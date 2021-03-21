<?php
namespace Test\Interaction\TRegx\CleanRegex\Match\Details;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\PhpunitPolyfill;
use Test\Utils\Impl\ThrowSubject;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatches;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\NotMatched;

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
     * @param string|int $nameOrIndex
     */
    public function shouldHasGroup($nameOrIndex)
    {
        // given
        $notMatched = $this->createNotMatched();

        // when
        $hasGroup = $notMatched->hasGroup($nameOrIndex);

        // then
        $this->assertTrue($hasGroup, "Failed asserting that group $nameOrIndex exists");
    }

    /**
     * @test
     * @dataProvider missingGroups
     * @param string|int $nameOrIndex
     */
    public function shouldHasGroup_not($nameOrIndex)
    {
        // given
        $notMatched = $this->createNotMatched();

        // when
        $hasGroup = $notMatched->hasGroup($nameOrIndex);

        // then
        $this->assertFalse($hasGroup, "Failed asserting that group $nameOrIndex is missing");
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
     * @param string|int $nameOrIndex
     * @param string $message
     */
    public function shouldThrow_invalidGroupName($nameOrIndex, string $message)
    {
        // given
        $notMatched = new NotMatched(new RawMatches([]), new ThrowSubject());

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        // when
        $notMatched->hasGroup($nameOrIndex);
    }

    public function invalidGroups(): array
    {
        return [
            [-1, 'Group index must be a non-negative integer, given: -1'],
            [-3, 'Group index must be a non-negative integer, given: -3'],
            ['2startingWithDigit', "Group name must be an alphanumeric string, not starting with a digit, given: '2startingWithDigit'"],
            ['dashed-dashed', "Group name must be an alphanumeric string, not starting with a digit, given: 'dashed-dashed'"]
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

<?php
namespace Test\Integration\TRegx\CleanRegex\Match\Details;

use InvalidArgumentException;
use PHPUnit\Framework\Error\Error;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatches;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Internal\SubjectableImpl;
use TRegx\CleanRegex\Match\Details\NotMatched;

class NotMatchedTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotCastToString()
    {
        // pre
        $previous = error_reporting(E_ALL);

        if (PHP_VERSION_ID <= 70100) {
            $this->markTestSkipped("Prior to PHP 7.1.0, casting to string causes a fatal error, which can't be tested by PhpUnit");
        }
        // given
        $notMatched = new NotMatched(new RawMatches([]), new SubjectableImpl('subject'));

        // then
        $this->expectException(Error::class);
        $this->expectExceptionMessage('Object of class TRegx\CleanRegex\Match\Details\NotMatched could not be converted to string');

        // when
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
        $notMatched = new NotMatched(new RawMatches([]), new SubjectableImpl('subject'));

        // when
        $subject = $notMatched->subject();

        // then
        $this->assertEquals('subject', $subject);
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
        /** @var Subjectable $subject */
        $subject = $this->createMock(Subjectable::class);
        $notMatched = new NotMatched(new RawMatches([]), $subject);

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        // when
        $notMatched->hasGroup($nameOrIndex);
    }

    public function invalidGroups(): array
    {
        return [
            [-1, 'Group index can only be a positive integer, given: -1'],
            [-3, 'Group index can only be a positive integer, given: -3'],
            ['2startingWithDigit', "Group name must be an alphanumeric string starting with a letter, given: '2startingWithDigit'"],
            ['dashed-dashed', "Group name must be an alphanumeric string starting with a letter, given: 'dashed-dashed'"]
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
        $this->assertEquals(['group', 'xd'], $groupNames);
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
        return new NotMatched(new RawMatches($matches), new SubjectableImpl('subject'));
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
        $this->assertEquals(4, $groupsCount);
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
        return new NotMatched(new RawMatches($matches), new SubjectableImpl('subject'));
    }
}

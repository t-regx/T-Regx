<?php
namespace Test\Unit\TRegx\CleanRegex\Match\Details;

use InvalidArgumentException;
use PHPUnit\Framework\Error\Error;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Model\RawMatches;
use TRegx\CleanRegex\Match\Details\NotMatched;

class NotMatchedTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotCastToString()
    {
        if (PHP_VERSION_ID <= 70100) {
            $this->markTestSkipped("Prior to PHP 7.1.0, casting to string causes a fatal error, which can't be tested by PhpUnit");
        }
        // given
        $notMatched = new NotMatched(new RawMatches([]), 'subject');

        // then
        $this->expectException(Error::class);
        $this->expectExceptionMessage('Object of class TRegx\CleanRegex\Match\Details\NotMatched could not be converted to string');

        // when
        $string = (string)$notMatched;
    }

    /**
     * @test
     */
    public function shouldGet_subject()
    {
        //
        $notMatched = new NotMatched(new RawMatches([]), 'subject');

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
        $notMatched = new NotMatched(new RawMatches([
            0       => [],
            'group' => [],
            1       => [],
            'xd'    => [],
            2       => [],
        ]), '');

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
        $notMatched = new NotMatched(new RawMatches([
            0       => [],
            'group' => [],
            1       => [],
            'xd'    => [],
            2       => [],
        ]), '');

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
     * @param string     $message
     */
    public function shouldThrow_invalidGroupName($nameOrIndex, string $message)
    {
        // given
        $notMatched = new NotMatched(new RawMatches([]), '');

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        // when
        $notMatched->hasGroup($nameOrIndex);
    }

    public function invalidGroups(): array
    {
        return [
            [-1, 'Negative limit -1'],
            [-3, 'Negative limit -3'],
        ];
    }

    /**
     * @test
     */
    public function shouldGet_groupNames()
    {
        // given
        $notMatched = new NotMatched(new RawMatches([
            0       => [],
            'group' => [],
            1       => [],
            'xd'    => [],
            2       => [],
        ]), 'subject');

        // when
        $groupNames = $notMatched->groupNames();

        // then
        $this->assertEquals(['group', 'xd'], $groupNames);
    }

    /**
     * @test
     */
    public function shouldGet_groupsCount()
    {
        // given
        $notMatched = new NotMatched(new RawMatches([
            0       => [],
            'group' => [],
            1       => [],
            2       => [],
            'xd'    => [],
            3       => [],
            4       => [],
        ]), 'subject');

        // when
        $groupsCount = $notMatched->groupsCount();

        // then
        $this->assertEquals(4, $groupsCount);
    }
}

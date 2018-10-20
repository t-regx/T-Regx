<?php
namespace Test\Unit\TRegx\CleanRegex\Match\Details;

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
    public function shouldGet_groupNames()
    {
        // given
        $notMatched = new NotMatched(new RawMatches([
            0       => ['Me'],
            'group' => ['M'],
            1       => ['M'],
            'xd'    => ['e'],
            2       => ['e'],
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
            0       => ['Me'],
            'group' => ['M'],
            1       => ['M'],
            2       => ['X'],
            'xd'    => ['X'],
            3       => ['e'],
            4       => ['f'],
        ]), 'subject');

        // when
        $groupsCount = $notMatched->groupsCount();

        // then
        $this->assertEquals(4, $groupsCount);
    }
}

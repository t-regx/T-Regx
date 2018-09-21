<?php
namespace Test\Unit\TRegx\CleanRegex\Match\Details;

use TRegx\CleanRegex\Match\Details\NotMatched;
use PHPUnit\Framework\Error\Error;
use PHPUnit\Framework\TestCase;

class NotMatchedTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotCastToString()
    {
        // given
        $notMatched = new NotMatched([], 'subject');

        // then
        $this->expectException(Error::class);
        $this->expectExceptionMessage('Object of class TRegx\CleanRegex\Match\Details\NotMatched could not be converted to string');

        // when
        $string = (string)$notMatched;
    }

    /**
     * @test
     */
    public function shouldGroupNames()
    {
        // given
        $notMatched = new NotMatched([
            0 => ['Me'],
            'group' => ['M'],
            1 => ['M'],
            'xd' => ['e'],
            2 => ['e'],
        ], 'subject');

        // when
        $groupNames = $notMatched->groupNames();

        // then
        $this->assertEquals(['group', 'xd'], $groupNames);
    }
}

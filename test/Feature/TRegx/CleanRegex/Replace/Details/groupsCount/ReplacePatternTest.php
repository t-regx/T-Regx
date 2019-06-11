<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\Details\groupsCount;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Match;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetGroupsCount()
    {
        // given
        pattern('(?<one>first) and (second)')
            ->replace('first and second')
            ->all()
            ->callback(function (Match $match) {
                // when
                $groupsCount = $match->groupsCount();

                // then
                $this->assertEquals(2, $groupsCount);

                // clean up
                return '';
            });
    }
}

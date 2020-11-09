<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\Details\groups;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetGroupsNames()
    {
        // given
        pattern('(zero) (?<existing>first) and (?<two_existing>second)')
            ->replace('zero first and second')
            ->all()
            ->callback(function (Detail $match) {
                // when
                $groupNames = $match->groups()->names();
                $namedGroups = $match->namedGroups()->names();

                // then
                $this->assertEquals([null, 'existing', 'two_existing'], $groupNames);
                $this->assertEquals(['existing', 'two_existing'], $namedGroups);

                // clean up
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldCount()
    {
        // given
        pattern('(zero) (?<existing>first) and (?<two_existing>second)')
            ->replace('zero first and second')
            ->all()
            ->callback(function (Detail $match) {
                // when
                $groups = $match->groups()->count();
                $namedGroups = $match->namedGroups()->count();

                // then
                $this->assertEquals(3, $groups);
                $this->assertEquals(2, $namedGroups);

                // clean up
                return '';
            });
    }
}

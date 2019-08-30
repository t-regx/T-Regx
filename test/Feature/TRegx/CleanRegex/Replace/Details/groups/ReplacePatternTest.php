<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\Details\groups;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Match;

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
            ->callback(function (Match $match) {
                // when
                $groupNames = $match->groups()->names();
                $namedGroups = $match->namedGroups()->names();

                // then
                $this->assertEquals($groupNames, [null, 'existing', 'two_existing']);
                $this->assertEquals($namedGroups, ['existing', 'two_existing']);

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
            ->callback(function (Match $match) {
                // when
                $groups = $match->groups()->count();
                $namedGroups = $match->namedGroups()->count();

                // then
                $this->assertEquals($groups, 3);
                $this->assertEquals($namedGroups, 2);

                // clean up
                return '';
            });
    }
}

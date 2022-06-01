<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\Details\groups;

use PHPUnit\Framework\TestCase;
use Test\Utils\DetailFunctions;
use TRegx\CleanRegex\Pattern;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetGroupsNames()
    {
        // given
        Pattern::of('(zero) (?<existing>first) and (?<two_existing>second)')
            ->replace('zero first and second')
            ->all()
            ->callback(DetailFunctions::out($detail, ''));
        // when
        $groupNames = $detail->groups()->names();
        $namedGroups = $detail->namedGroups()->names();
        // then
        $this->assertSame([null, 'existing', 'two_existing'], $groupNames);
        $this->assertSame(['existing', 'two_existing'], $namedGroups);
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
            ->callback(DetailFunctions::out($detail, ''));
        // when
        $groups = $detail->groups()->count();
        $namedGroups = $detail->namedGroups()->count();
        // then
        $this->assertSame(3, $groups);
        $this->assertSame(2, $namedGroups);
    }
}

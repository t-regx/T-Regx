<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\Details\groups;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsGroup;
use Test\Utils\DetailFunctions;
use TRegx\CleanRegex\Pattern;

class ReplacePatternTest extends TestCase
{
    use AssertsGroup;

    /**
     * @test
     */
    public function shouldGetGroups()
    {
        // given
        Pattern::of('(zero) (?<existing>first) and (?<two_existing>second)')
            ->replace('zero first and second')
            ->callback(DetailFunctions::out($detail, ''));
        // when, then
        $this->assertGroupNames([null, 'existing', 'two_existing'], $detail->groups());
        $this->assertGroupNames(['existing' => 'existing', 'two_existing' => 'two_existing'], $detail->namedGroups());
    }
}

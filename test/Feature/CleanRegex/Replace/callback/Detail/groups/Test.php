<?php
namespace Test\Feature\CleanRegex\Replace\callback\Detail\groups;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsGroup;
use Test\Utils\DetailFunctions;
use TRegx\CleanRegex\Pattern;

class Test extends TestCase
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

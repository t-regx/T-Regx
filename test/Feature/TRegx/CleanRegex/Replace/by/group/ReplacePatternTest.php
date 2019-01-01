<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\by\group;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrow_onInvalidGroupName()
    {
        // given
        $groupName = '2group';

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string starting with a letter, given: '2group'");

        // when
        pattern('(?<capital>[OT])(ne|wo)')
            ->replace('')
            ->all()
            ->by()
            ->group($groupName)
            ->map([]);
    }
}

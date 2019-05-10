<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\by\group;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\CleanRegex\NonexistentGroupException;

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

    /**
     * @test
     */
    public function shouldThrow_onNonExistingGroup()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        pattern('(?<capital>foo)')
            ->replace('foo')
            ->all()
            ->by()
            ->group('missing')
            ->map([]);
    }

    /**
     * @test
     */
    public function shouldThrow_groupNotMatch()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to replace with group '1', but the group was not matched");

        // when
        pattern('Foo(?<bar>Bar)?')
            ->replace('Foo')
            ->all()
            ->by()
            ->group('bar')
            ->map([]);
    }
}

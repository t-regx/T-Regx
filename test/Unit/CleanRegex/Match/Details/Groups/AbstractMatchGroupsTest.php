<?php
namespace Test\Unit\TRegx\CleanRegex\Match\Details\Groups;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Model\Match\ConstantEntries;
use Test\Fakes\CleanRegex\Internal\Model\ThrowGroupAware;
use Test\Fakes\CleanRegex\Internal\ThrowSubject;
use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Match\Details\Groups\IndexedGroups;

/**
 * @covers \TRegx\CleanRegex\Match\Details\Groups\AbstractMatchGroups
 */
class AbstractMatchGroupsTest extends TestCase
{
    /**
     * @test
     * @dataProvider invalidGroupValues
     * @param mixed $invalidValue
     */
    public function shouldThrowInternal($invalidValue)
    {
        // given
        $matchGroups = new IndexedGroups(new ThrowGroupAware(), new ConstantEntries(['first', $invalidValue]), new ThrowSubject());

        // then
        $this->expectException(InternalCleanRegexException::class);

        // when
        $matchGroups->texts();
    }

    public function invalidGroupValues(): array
    {
        return [
            [-2],
            [[]],
            [new \stdClass()]
        ];
    }
}

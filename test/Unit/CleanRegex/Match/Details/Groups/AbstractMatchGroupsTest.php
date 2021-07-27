<?php
namespace Test\Unit\TRegx\CleanRegex\Match\Details\Groups;

use PHPUnit\Framework\TestCase;
use Test\Utils\Impl\ConstantRenameMe;
use Test\Utils\Impl\ThrowGroupAware;
use Test\Utils\Impl\ThrowSubject;
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
        $matchGroups = new IndexedGroups(new ThrowGroupAware(), new ConstantRenameMe(['first', $invalidValue]), new ThrowSubject());

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

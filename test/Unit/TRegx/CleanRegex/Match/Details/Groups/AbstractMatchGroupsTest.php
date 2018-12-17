<?php
namespace Test\Unit\TRegx\CleanRegex\Match\Details\Groups;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\SubjectableImpl;
use TRegx\CleanRegex\Match\Details\Groups\AbstractMatchGroups;
use TRegx\CleanRegex\Match\Details\Groups\IndexedGroups;

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
        $matchGroups = $this->getMatchGroups([
            0 => 'first second',
            1 => $invalidValue
        ]);

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

    private function getMatchGroups(array $texts): AbstractMatchGroups
    {
        /** @var IRawMatchOffset|MockObject $mock */
        $mock = $this->createMock(IRawMatchOffset::class);
        $mock->method('getGroupsTexts')->willReturn($texts);

        return new IndexedGroups($mock, new SubjectableImpl(''));
    }
}

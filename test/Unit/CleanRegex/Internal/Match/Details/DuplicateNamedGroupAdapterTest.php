<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Details;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Match\Details\DuplicateNamedGroupAdapter;
use TRegx\CleanRegex\Match\Details\Group\Group;

/**
 * @covers \TRegx\CleanRegex\Internal\Match\Details\DuplicateNamedGroupAdapter
 */
class DuplicateNamedGroupAdapterTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetName(): void
    {
        // given
        $adapter = new DuplicateNamedGroupAdapter('foo', $this->matchGroup('name', [], 'bar', 0));

        // when
        $name = $adapter->name();

        // then
        $this->assertSame('foo', $name);
    }

    /**
     * @test
     * @dataProvider methods
     * @param string $method
     * @param array $arguments
     * @param $value
     */
    public function shouldDelegate(string $method, array $arguments, $value): void
    {
        // given
        $adapter = new DuplicateNamedGroupAdapter('', $this->matchGroup($method, $arguments, $value));

        // when
        $result = $adapter->$method(...$arguments);

        // then
        $this->assertSame($value, $result);
    }

    public function methods(): array
    {
        return [
            'text'           => ['text', [], 'value'],
            'textLength'     => ['textLength', [], 14],
            'textByteLength' => ['textByteLength', [], 14],
            'toInt'          => ['toInt', [], 14],
            'isInt'          => ['isInt', [], true],
            'equals'         => ['equals', ['arg'], true],
            'substitute'     => ['substitute', ['arg'], 'value'],
            'matched'        => ['matched', [], true],
            'usedIdentifier' => ['usedIdentifier', [], 12],
            'offset'         => ['offset', [], 12],
            'tail'           => ['tail', [], 12],
            'byteOffset'     => ['byteOffset', [], 12],
            'byteTail'       => ['byteTail', [], 12],
            'subject'        => ['subject', [], 'the subject'],
            'all'            => ['all', [], ['a', 'b']],
            'orThrow []'     => ['orThrow', [], 'substitute'],
            'orThrow [arg]'  => ['orThrow', [\stdClass::class], 'substitute'],
            'orReturn'       => ['orReturn', ['substitute'], 'substitute'],
            'orElse'         => ['orElse', ['strToUpper'], 'substitute'],
        ];
    }

    private function matchGroup(string $method, $arguments, $result, int $count = 1): Group
    {
        /** @var MockObject|Group $group */
        $group = $this->createMock(Group::class);

        $group
            ->expects($this->exactly($count))
            ->method($method)
            ->with(...$arguments)
            ->will($this->returnValue($result));

        return $group;
    }
}

<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Details;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Match\Details\Group\IntGroup;
use Test\Fakes\CleanRegex\Match\Details\Group\IsIntGroup;
use TRegx\CleanRegex\Internal\Match\Details\DuplicateNamedGroupAdapter;
use TRegx\CleanRegex\Match\Details\Group\Group;

/**
 * @covers \TRegx\CleanRegex\Internal\Match\Details\DuplicateNamedGroupAdapter
 */
class DuplicateNamedGroupAdapterTest extends TestCase
{

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
            'length'         => ['length', [], 14],
            'textByteLength' => ['textByteLength', [], 14],
            'toInt base 10'  => ['toInt', [], 14],
            'toInt base 3'   => ['toInt', [3], 14],
            'isInt base 10'  => ['isInt', [], true],
            'isInt base 3'   => ['isInt', [3], true],
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
            'or'             => ['or', ['substitute'], 'substitute'],
        ];
    }

    /**
     * @deprecated
     */
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

    /**
     * @test
     */
    public function shouldGetInt(): void
    {
        // given
        $adapter = new DuplicateNamedGroupAdapter('foo', new IntGroup(12, 10));

        // when
        $integer = $adapter->toInt();

        // then
        $this->assertSame(12, $integer);
    }

    /**
     * @test
     */
    public function shouldGetIntBase4(): void
    {
        // given
        $adapter = new DuplicateNamedGroupAdapter('foo', new IntGroup(14, 4));

        // when
        $integer = $adapter->toInt(4);

        // then
        $this->assertSame(14, $integer);
    }

    /**
     * @test
     */
    public function shouldBeInt(): void
    {
        // given
        $adapter = new DuplicateNamedGroupAdapter('foo', new IntGroup(14, 4));

        // when, then
        $this->assertSame(14, $adapter->toInt(4));
    }

    /**
     * @test
     */
    public function shouldBeIntDefaultBase(): void
    {
        // given
        $adapter = new DuplicateNamedGroupAdapter('foo', new IntGroup(13, 10));

        // when, then
        $this->assertSame(13, $adapter->toInt());
    }

    /**
     * @test
     */
    public function shouldNotBeInt(): void
    {
        // given
        $adapter = new DuplicateNamedGroupAdapter('foo', new IsIntGroup(false, 10));

        // when, then
        $this->assertFalse($adapter->isInt());
    }

    /**
     * @test
     */
    public function shouldNotBeIntBase16(): void
    {
        // given
        $adapter = new DuplicateNamedGroupAdapter('foo', new IsIntGroup(false, 16));

        // when, then
        $this->assertFalse($adapter->isInt(16));
    }

    /**
     * @test
     */
    public function shouldBeIntBase10(): void
    {
        // given
        $adapter = new DuplicateNamedGroupAdapter('foo', new IsIntGroup(true, 10));

        // when, then
        $this->assertTrue($adapter->isInt(10));
    }
}

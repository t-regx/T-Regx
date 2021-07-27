<?php
namespace Test\Interaction\TRegx\CleanRegex\Internal;

use PHPUnit\Framework\TestCase;
use Test\Utils\Impl\GroupKeys;
use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\GroupKey\GroupIndex;
use TRegx\CleanRegex\Internal\GroupKey\GroupName;
use TRegx\CleanRegex\Internal\GroupNameIndexAssign;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;

/**
 * @covers \TRegx\CleanRegex\Internal\GroupNameIndexAssign
 */
class GroupNameIndexAssignTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCallFactory_byName_uneven()
    {
        // given
        $assign = $this->createWithMatchAllFactory_uneven();

        // when
        [$name, $index] = $assign->getNameAndIndex(new GroupName('name2'));

        // then
        $this->assertSame('name2', $name);
        $this->assertSame(3, $index);
    }

    /**
     * @test
     */
    public function shouldCallFactory_byIndex_uneven()
    {
        // given
        $assign = $this->createWithMatchAllFactory_uneven();

        // when
        [$name, $index] = $assign->getNameAndIndex(new GroupIndex(3));

        // then
        $this->assertSame('name2', $name);
        $this->assertSame(3, $index);
    }

    private function createWithMatchAllFactory_uneven(): GroupNameIndexAssign
    {
        $matches = [
            0       => null,
            1       => null,
            'name'  => null,
            2       => null,
            'name2' => null,
            3       => null,
        ];

        return new GroupNameIndexAssign(new GroupKeys(\array_keys($matches)), new EagerMatchAllFactory(new RawMatchesOffset($matches)));
    }

    /**
     * @test
     */
    public function shouldCallFactory_byName_even()
    {
        // given
        $assign = $this->createWithMatchAllFactory_even();

        // when
        [$name, $index] = $assign->getNameAndIndex(new GroupName('name2'));

        // then
        $this->assertSame('name2', $name);
        $this->assertSame(3, $index);
    }

    /**
     * @test
     */
    public function shouldCallFactory_byIndex_even()
    {
        // given
        $assign = $this->createWithMatchAllFactory_even();

        // when
        [$name, $index] = $assign->getNameAndIndex(new GroupIndex(3));

        // then
        $this->assertSame('name2', $name);
        $this->assertSame(3, $index);
    }

    private function createWithMatchAllFactory_even(): GroupNameIndexAssign
    {
        $matches = [
            0       => null,
            1       => null,
            'name'  => null,
            2       => null,
            'name2' => null,
            3       => null,
        ];
        return new GroupNameIndexAssign(new GroupKeys(\array_keys($matches)), new EagerMatchAllFactory(new RawMatchesOffset($matches)));
    }

    /**
     * @test
     */
    public function shouldCallFactory_byName_missing()
    {
        // given
        $assign = $this->createWithMatchAllFactory_uneven();

        // then
        $this->expectException(InternalCleanRegexException::class);

        // when
        $assign->getNameAndIndex(new GroupName('missing'));
    }

    /**
     * @test
     */
    public function shouldCallFactory_byIndex_missing()
    {
        // given
        $assign = $this->createWithMatchAllFactory_uneven();

        // then
        $this->expectException(InternalCleanRegexException::class);

        // when
        $assign->getNameAndIndex(new GroupIndex(14));
    }
}

<?php
namespace Test\Integration\TRegx\CleanRegex\Internal;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\ExceptionMatchAllFactory;
use TRegx\CleanRegex\Internal\GroupNameIndexAssign;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;

class GroupNameIndexAssignTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetNameAndIndex_fromName()
    {
        // given
        $assign = $this->create();

        // when
        list($name, $index) = $assign->getNameAndIndex('third');

        // then
        $this->assertEquals('third', $name);
        $this->assertEquals(3, $index);
    }

    /**
     * @test
     */
    public function shouldGetNameAndIndex_fromIndex()
    {
        // given
        $assign = $this->create();

        // when
        list($name, $index) = $assign->getNameAndIndex(5);

        // then
        $this->assertEquals('fifth', $name);
        $this->assertEquals(5, $index);
    }

    /**
     * @test
     */
    public function shouldGetNullAndIndex_fromIndex_unnamedGroup()
    {
        // given
        $assign = $this->create();

        // when
        list($name, $index) = $assign->getNameAndIndex(2);

        // then
        $this->assertEquals(null, $name);
        $this->assertEquals(2, $index);
    }

    /**
     * @test
     */
    public function shouldGetNullAndIndex_fromIndex_wholeMatch()
    {
        // given
        $assign = $this->create();

        // when
        list($name, $index) = $assign->getNameAndIndex(0);

        // then
        $this->assertEquals(null, $name);
        $this->assertEquals(0, $index);
    }

    /**
     * @test
     */
    public function shouldThrow_onInvalidArgument()
    {
        // given
        $assign = $this->create();

        // then
        $this->expectException(InvalidArgumentException::class);

        // when
        $assign->getNameAndIndex(true);
    }

    /**
     * @param string|int $group
     * @return GroupNameIndexAssign
     */
    private function create(): GroupNameIndexAssign
    {
        $matches = [
            0       => [],
            'first' => [],
            1       => [],
            2       => [],
            'third' => [],
            3       => [],
            4       => [],
            'fifth' => [],
            5       => [],
        ];
        $factory = new ExceptionMatchAllFactory();
        return new GroupNameIndexAssign(new RawMatchOffset($matches), $factory);
    }
}

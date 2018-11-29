<?php
namespace Test\UnitTRegx\CleanRegex\Internal;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\GroupNameIndexAssign;
use TRegx\CleanRegex\Internal\Match\MatchAll\ExceptionMatchAllFactory;
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

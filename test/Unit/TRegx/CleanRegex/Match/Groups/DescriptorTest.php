<?php
namespace Test\Unit\TRegx\CleanRegex\Groups;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Match\Groups\Descriptor;

class DescriptorTest extends TestCase
{
    /**
     * @test
     */
    public function should_getGroup()
    {
        // given
        $descriptor = new Descriptor(InternalPattern::standard('Pattern'));

        // when
        $groups = $descriptor->getGroups();

        // then
        $this->assertEquals([0], $groups);
    }

    /**
     * @test
     */
    public function should_getGroups()
    {
        // given
        $descriptor = new Descriptor(InternalPattern::standard('(First) (?<named>Second) (Third) (?<Fourth>. (?<nested>))'));

        // when
        $groups = $descriptor->getGroups();

        // then
        $this->assertEquals([0, 1, 'named', 2, 3, 'Fourth', 4, 'nested', 5], $groups);
    }

    /**
     * @test
     */
    public function should_haveGroups()
    {
        // given
        $descriptor = new Descriptor(InternalPattern::standard('Just a pattern with (group)'));

        // when
        $has = $descriptor->hasAnyGroup();

        // then
        $this->assertTrue($has);
    }

    /**
     * @test
     */
    public function should_not_haveGroups()
    {
        // given
        $descriptor = new Descriptor(InternalPattern::standard('Just a pattern'));

        // when
        $has = $descriptor->hasAnyGroup();

        // then
        $this->assertFalse($has);
    }
}

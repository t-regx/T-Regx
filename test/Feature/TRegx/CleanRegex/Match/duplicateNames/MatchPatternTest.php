<?php
namespace Test\Feature\TRegx\CleanRegex\Match\duplicateNames;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetText()
    {
        // given
        $match = $this->match();

        // when
        $declared = $match->group('group');
        $parsed = $match->usingDuplicateName()->group('group');

        // then
        $this->assertEquals('One', $declared->text());
        $this->assertEquals('Two', $parsed->text());
    }

    /**
     * @test
     */
    public function shouldGetOffset()
    {
        // given
        $match = $this->match();

        // when
        $declared = $match->group('group');
        $parsed = $match->usingDuplicateName()->group('group');

        // then
        $this->assertEquals(0, $declared->offset());
        $this->assertEquals(3, $parsed->offset());
    }

    /**
     * @test
     */
    public function shouldGetName()
    {
        // given
        $match = $this->match();

        // when
        $declared = $match->group('group');
        $parsed = $match->usingDuplicateName()->group('group');

        // then
        $this->assertEquals('group', $declared->name());
        $this->assertEquals('group', $parsed->name());
    }

    /**
     * @test
     */
    public function shouldGetIndex()
    {
        // given
        $match = $this->match();

        // when
        $declared = $match->group('group');

        // then
        $this->assertEquals(1, $declared->index());
    }

    public function match(): Detail
    {
        return pattern('(?<group>One)(?<group>Two)', 'J')
            ->match('OneTwo')
            ->fluent()
            ->first();
    }
}

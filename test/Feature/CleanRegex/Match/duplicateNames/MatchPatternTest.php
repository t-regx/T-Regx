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
        $detail = $this->detail();

        // when
        $declared = $detail->group('group');
        $parsed = $detail->usingDuplicateName()->group('group');

        // then
        $this->assertSame('One', $declared->text());
        $this->assertSame('Two', $parsed->text());
    }

    /**
     * @test
     */
    public function shouldGetOffset()
    {
        // given
        $detail = $this->detail();

        // when
        $declared = $detail->group('group');
        $parsed = $detail->usingDuplicateName()->group('group');

        // then
        $this->assertSame(0, $declared->offset());
        $this->assertSame(3, $parsed->offset());
    }

    /**
     * @test
     */
    public function shouldGetName()
    {
        // given
        $detail = $this->detail();

        // when
        $declared = $detail->group('group');
        $parsed = $detail->usingDuplicateName()->group('group');

        // then
        $this->assertSame('group', $declared->name());
        $this->assertSame('group', $parsed->name());
    }

    /**
     * @test
     */
    public function shouldGetIndex()
    {
        // given
        $detail = $this->detail();

        // when
        $declared = $detail->group('group');

        // then
        $this->assertSame(1, $declared->index());
    }

    public function detail(): Detail
    {
        return pattern('(?<group>One)(?<group>Two)', 'J')
            ->match('OneTwo')
            ->fluent()
            ->first();
    }
}

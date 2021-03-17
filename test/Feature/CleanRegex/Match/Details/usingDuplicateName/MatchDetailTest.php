<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\usingDuplicateName;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

class MatchDetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGet_text()
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
    public function shouldGet_get()
    {
        // given
        $detail = $this->detail();

        // when
        $declared = $detail->get('group');
        $parsed = $detail->usingDuplicateName()->get('group');

        // then
        $this->assertSame('One', $declared);
        $this->assertSame('Two', $parsed);
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

    /**
     * @test
     */
    public function shouldThrow_group_InvalidGroupName()
    {
        // given
        $detail = $this->detail();

        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, given: '!@#'");

        // when
        $detail->usingDuplicateName()->group('!@#');
    }

    /**
     * @test
     */
    public function shouldThrow_group_IntegerGroup()
    {
        // given
        $detail = $this->detail();

        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, given: '2'");

        // when
        $detail->usingDuplicateName()->group(2);
    }

    /**
     * @test
     */
    public function shouldThrow_get_InvalidGroupName()
    {
        // given
        $detail = $this->detail();

        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, given: '!@#'");

        // when
        $detail->usingDuplicateName()->get('!@#');
    }

    /**
     * @test
     */
    public function shouldThrow_get_IntegerGroup()
    {
        // given
        $detail = $this->detail();

        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, given: '2'");

        // when
        $detail->usingDuplicateName()->get(2);
    }
}

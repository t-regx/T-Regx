<?php
namespace Test\Feature\CleanRegex\replace\callback\Detail\offset;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsGroup;
use Test\Utils\DetailFunctions;
use Test\Utils\Functions;
use TRegx\CleanRegex\Pattern;

class Test extends TestCase
{
    use AssertsGroup;

    /**
     * @test
     */
    public function shouldGetOffset_first()
    {
        // given
        $pattern = Pattern::of('Tomek');
        $replace = $pattern->replace('€€€€, Tomek');
        // when
        $replace->callback(Functions::out($detail, ''));
        // then
        $this->assertSame(6, $detail->offset());
        $this->assertSame(14, $detail->byteOffset());
    }

    /**
     * @test
     */
    public function shouldGetOffset_second()
    {
        // given
        $pattern = Pattern::of('Tomek|Kamil');
        $replace = $pattern->replace('€€€€, Tomek i Kamil');
        // when
        $replace->callback(DetailFunctions::outLast($detail, ''));
        // then
        $this->assertSame(14, $detail->offset());
        $this->assertSame(22, $detail->byteOffset());
    }

    /**
     * @test
     */
    public function shouldGet_compositeGroups_offset()
    {
        // when
        Pattern::of('(?<group>Foo)')->replace('€ ęFoo')->callback(Functions::out($detail, ''));
        // then
        $this->assertGroupOffsets([3], $detail->groups());
        $this->assertGroupIndicesConsequetive($detail->groups());
        $this->assertGroupOffsets(['group' => 3], $detail->namedGroups());
    }

    /**
     * @test
     */
    public function shouldGetTail()
    {
        // given
        $pattern = Pattern::of('(Tońe|Kamy)k');
        $replace = $pattern->replace('€€€€, Tońek i Kamyk');
        // when
        $replace->callback(Functions::collect($details, ''));
        // then
        [$first, $second] = $details;
        $this->assertSame(11, $first->tail());
        $this->assertSame(20, $first->byteTail());
        $this->assertSame(19, $second->tail());
        $this->assertSame(28, $second->byteTail());
    }

    /**
     * @test
     */
    public function shouldGetLength()
    {
        // given
        $pattern = Pattern::of('(Tońe|Kamy)k');
        $replace = $pattern->replace('€€€€, Tońek i Kamyk');
        // when
        $replace->callback(Functions::collect($details, ''));
        // then
        [$first, $second] = $details;
        $this->assertSame(5, $first->length());
        $this->assertSame(6, $first->byteLength());
        $this->assertSame(5, $second->length());
        $this->assertSame(5, $second->byteLength());
    }
}

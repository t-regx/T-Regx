<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\Details\offset;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsGroup;
use Test\Utils\DetailFunctions;

class ReplacePatternTest extends TestCase
{
    use AssertsGroup;

    /**
     * @test
     */
    public function shouldGetOffset_first()
    {
        // when
        pattern('Tomek')
            ->replace('€€€€, Tomek')
            ->first()
            ->callback(DetailFunctions::out($detail, ''));
        // when
        $offset = $detail->offset();
        $byteOffset = $detail->byteOffset();
        // then
        $this->assertSame(6, $offset);
        $this->assertSame(14, $byteOffset);
    }

    /**
     * @test
     */
    public function shouldGetOffset_forEach()
    {
        // when
        pattern('(Tomek|Kamil)')
            ->replace('€€€€, Tomek i Kamil')
            ->all()
            ->callback(DetailFunctions::outLast($detail, ''));
        // when
        $offset = $detail->offset();
        $byteOffset = $detail->byteOffset();
        // then
        $this->assertSame(14, $offset);
        $this->assertSame(22, $byteOffset);
    }

    /**
     * @test
     */
    public function shouldGet_compositeGroups_offset()
    {
        // when
        pattern('(?<group>Foo)')->replace('€ ęFoo')->callback(DetailFunctions::out($detail, ''));
        // then
        $this->assertGroupOffsets([3], $detail->groups());
        $this->assertGroupIndicesConsequetive($detail->groups());
        $this->assertGroupOffsets(['group' => 3], $detail->namedGroups());
    }
}

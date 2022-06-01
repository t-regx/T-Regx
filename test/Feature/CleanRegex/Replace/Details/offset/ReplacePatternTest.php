<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\Details\offset;

use PHPUnit\Framework\TestCase;
use Test\Utils\DetailFunctions;

class ReplacePatternTest extends TestCase
{
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
        // given
        $subject = '€ ęFoo'; // Subject chosen to expose errors with byte-offset bugs
        pattern('(?<group>Foo)')->replace($subject)->first()->callback(DetailFunctions::out($detail, ''));
        // when
        $indexedOffsets = $detail->groups()->offsets();
        $namedOffsets = $detail->namedGroups()->offsets();
        // then
        $this->assertSame([3], $indexedOffsets);
        $this->assertSame(['group' => 3], $namedOffsets);
    }
}

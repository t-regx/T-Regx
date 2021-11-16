<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\Details\offset;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

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
            ->callback(function (Detail $detail) {
                // when
                $offset = $detail->offset();
                $byteOffset = $detail->byteOffset();

                // then
                $this->assertSame(6, $offset);
                $this->assertSame(14, $byteOffset);

                // clean
                return '';
            });
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
            ->callback(function (Detail $detail) {
                if ($detail->index() !== 1) return '';

                // when
                $offset = $detail->offset();
                $byteOffset = $detail->byteOffset();

                // then
                $this->assertSame(14, $offset);
                $this->assertSame(22, $byteOffset);

                // clean
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldGet_compositeGroups_offset()
    {
        // given
        $subject = '€ ęFoo'; // Subject specifically designed to expose errors with byte-offset bugs

        // when
        pattern('(?<group>Foo)')->replace($subject)->first()->callback(function (Detail $detail) {
            // when
            $indexedOffsets = $detail->groups()->offsets();
            $namedOffsets = $detail->namedGroups()->offsets();

            // then
            $this->assertSame([3], $indexedOffsets);
            $this->assertSame(['group' => 3], $namedOffsets);

            // clean
            return '';
        });
    }
}

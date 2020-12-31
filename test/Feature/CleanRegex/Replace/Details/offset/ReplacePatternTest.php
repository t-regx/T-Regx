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
}

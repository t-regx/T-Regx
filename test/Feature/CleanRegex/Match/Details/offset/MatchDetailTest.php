<?php
namespace Test\Feature\CleanRegex\Match\Details\offset;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

class MatchDetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetOffset_first()
    {
        // given
        $detail = pattern('Tomek')->match('€€€€, Tomek')->first();
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
            ->match('€€€€, Tomek i Kamil')
            ->forEach(function (Detail $detail) {
                if ($detail->index() !== 1) return;

                // when
                $offset = $detail->offset();
                $byteOffset = $detail->byteOffset();

                // then
                $this->assertSame(14, $offset);
                $this->assertSame(22, $byteOffset);
            });
    }
}

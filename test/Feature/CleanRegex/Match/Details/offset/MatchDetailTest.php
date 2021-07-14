<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\offset;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

/**
 * @coversNothing
 */
class MatchDetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetOffset_first()
    {
        // when
        pattern('Tomek')
            ->match('€€€€, Tomek')
            ->first(function (Detail $detail) {
                // when
                $offset = $detail->offset();
                $byteOffset = $detail->byteOffset();

                // then
                $this->assertSame(6, $offset);
                $this->assertSame(14, $byteOffset);
            });
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

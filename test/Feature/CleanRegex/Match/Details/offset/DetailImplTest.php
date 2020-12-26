<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\offset;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

class DetailImplTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetOffset_first()
    {
        // when
        pattern('\w{4,}')
            ->match('Cześć, Tomek')
            ->first(function (Detail $detail) {
                // when
                $offset = $detail->offset();
                $byteOffset = $detail->byteOffset();

                // then
                $this->assertSame(7, $offset);
                $this->assertSame(9, $byteOffset);
            });
    }

    /**
     * @test
     */
    public function shouldGetOffset_forEach()
    {
        // when
        pattern('\w{4,}')
            ->match('Cześć, Tomek i Kamil')
            ->forEach(function (Detail $detail) {
                if ($detail->index() !== 1) return;

                // when
                $offset = $detail->offset();
                $byteOffset = $detail->byteOffset();

                // then
                $this->assertSame(15, $offset);
                $this->assertSame(17, $byteOffset);
            });
    }
}

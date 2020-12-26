<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\group\offset;

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
        pattern('(?<capital>[A-Z])(?<lowercase>[a-z]{3,})')
            ->match('Cześć, Tomek')
            ->first(function (Detail $detail) {
                // when
                $offset = $detail->group('lowercase')->offset();
                $byteOffset = $detail->group('lowercase')->byteOffset();

                // then
                $this->assertSame(8, $offset);
                $this->assertSame(10, $byteOffset);
            });
    }

    /**
     * @test
     */
    public function shouldGetOffset_forEach()
    {
        // when
        pattern('(?<capital>[A-Z])(?<lowercase>[a-z]{3,})')
            ->match('Cześć, Tomek i Kamil')
            ->forEach(function (Detail $detail) {
                if ($detail->index() !== 1) return;

                // when
                $offset = $detail->group('lowercase')->offset();
                $byteOffset = $detail->group('lowercase')->byteOffset();

                // then
                $this->assertSame(16, $offset);
                $this->assertSame(18, $byteOffset);
            });
    }
}

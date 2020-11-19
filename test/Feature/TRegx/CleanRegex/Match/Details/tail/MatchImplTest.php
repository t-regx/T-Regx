<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\tail;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

class MatchImplTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetTail_first()
    {
        // when
        pattern('K[^ ]+')
            ->match(' Cześć, Kraśko ')
            ->first(function (Detail $detail) {
                // given
                $this->assertEquals("Kraśko", $detail);

                // when
                $tail = $detail->tail();
                $byteTail = $detail->byteTail();

                // then
                $this->assertEquals(14, $tail);
                $this->assertEquals(17, $byteTail);
            });
    }

    /**
     * @test
     */
    public function shouldGetOffset_forEach()
    {
        // when
        pattern('K[^ ]+')
            ->match('Cześć, Kraśko i Księciuniu')
            ->forEach(function (Detail $detail) {
                // given
                if ($detail->index() !== 1) return;
                $this->assertEquals("Księciuniu", $detail);

                // when
                $tail = $detail->tail();
                $byteTail = $detail->byteTail();

                // then
                $this->assertEquals(26, $tail);
                $this->assertEquals(30, $byteTail);
            });
    }
}

<?php
namespace Test\Feature\CleanRegex\Match\Details\tail;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

class MatchDetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetTail_first()
    {
        // given
        $detail = pattern('K[^ ]+')->match(' Cześć, Kraśko ')->first();
        // when
        $tail = $detail->tail();
        $byteTail = $detail->byteTail();
        // then
        $this->assertSame("Kraśko", "$detail");
        $this->assertSame(14, $tail);
        $this->assertSame(17, $byteTail);
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
                $this->assertSame("Księciuniu", "$detail");

                // when
                $tail = $detail->tail();
                $byteTail = $detail->byteTail();

                // then
                $this->assertSame(26, $tail);
                $this->assertSame(30, $byteTail);
            });
    }
}

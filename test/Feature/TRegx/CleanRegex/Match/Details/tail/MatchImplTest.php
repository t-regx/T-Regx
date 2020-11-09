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
            ->first(function (Detail $match) {
                // given
                $this->assertEquals("Kraśko", $match);

                // when
                $tail = $match->tail();
                $byteTail = $match->byteTail();

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
            ->forEach(function (Detail $match) {
                // given
                if ($match->index() !== 1) return;
                $this->assertEquals("Księciuniu", $match);

                // when
                $tail = $match->tail();
                $byteTail = $match->byteTail();

                // then
                $this->assertEquals(26, $tail);
                $this->assertEquals(30, $byteTail);
            });
    }
}

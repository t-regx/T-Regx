<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\group\offset;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Match;

class MatchImplTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetOffset_first()
    {
        // when
        pattern('(?<capital>[A-Z])(?<lowercase>[a-z]{3,})')
            ->match('Cześć, Tomek')
            ->first(function (Match $match) {

                // when
                $offset = $match->group('lowercase')->offset();
                $byteOffset = $match->group('lowercase')->byteOffset();

                // then
                $this->assertEquals(8, $offset);
                $this->assertEquals(10, $byteOffset);
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
            ->forEach(function (Match $match) {
                if ($match->index() !== 1) return;

                // when
                $offset = $match->group('lowercase')->offset();
                $byteOffset = $match->group('lowercase')->byteOffset();

                // then
                $this->assertEquals(16, $offset);
                $this->assertEquals(18, $byteOffset);
            });
    }
}

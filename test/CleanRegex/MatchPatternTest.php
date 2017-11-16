<?php
namespace CleanRegex;

use CleanRegex\Match\Match;
use PHPUnit\Framework\TestCase;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAllMatches()
    {
        // when
        $matches = pattern('Foo (B(ar))')->match('Foo Bar, Foo Bar, Foo Bar')->all();

        // then
        $this->assertEquals(['Foo Bar', 'Foo Bar', 'Foo Bar'], $matches);
    }

    /**
     * @test
     */
    public function shouldMatchAllForFirst()
    {
        // when
        $matches = pattern('(?<capital>[A-Z])(?<lowercase>[a-z]+)')
            ->match('Foo, Leszek Ziom, Dupa')
            ->first(function (Match $match) {

                // then
                $this->assertEquals(['Foo', 'Leszek', 'Ziom', 'Dupa'], $match->all());

            });
    }
}

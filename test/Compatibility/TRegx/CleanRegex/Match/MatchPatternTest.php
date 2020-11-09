<?php
namespace Test\Compatibility\TRegx\CleanRegex\Match;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\DetailImpl;
use TRegx\CleanRegex\Match\Details\Match;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCallAsMatch()
    {
        // when
        pattern('[A-Za-z]{4}\.')->match('What do you need? - Guns.')->first(function (Match $match) {
            $this->assertInstanceOf(DetailImpl::class, $match);
            $this->assertInstanceOf(Detail::class, $match);
        });
    }
}

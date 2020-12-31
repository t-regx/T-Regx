<?php
namespace Test\Compatibility\TRegx\CleanRegex\Match;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\DetailImpl;
use TRegx\CleanRegex\Match\Details\Group\DetailGroup;
use TRegx\CleanRegex\Match\Details\Group\MatchedGroup;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;
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

    /**
     * @test
     */
    public function shouldCallAsMatchGroup()
    {
        // when
        pattern('([A-Za-z]){4}\.')->match('What do you need? - Guns.')
            ->group(1)
            ->first(function (MatchGroup $match) {
                $this->assertInstanceOf(MatchedGroup::class, $match);
                $this->assertInstanceOf(DetailGroup::class, $match);
            });
    }
}

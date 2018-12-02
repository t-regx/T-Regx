<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\userData;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Match;

class MatchImplTest extends TestCase
{
    /**
     * @test
     */
    public function shouldPreserveUserData_first()
    {
        // given
        $filtered = pattern('[A-Z][a-z]+')
            ->match('First, Second, Third')
            ->filter(function (Match $match) {
                $match->setUserData($match . $match);
                return true;
            });

        // when
        $userData = $filtered->first(function (Match $match) {
            return $match->getUserData();
        });

        // then
        $this->assertEquals('FirstFirst', $userData);
    }

    /**
     * @test
     */
    public function shouldPreserveUserData_forFirst()
    {
        // given
        $filtered = pattern('[A-Z][a-z]+')
            ->match('First, Second, Third')
            ->filter(function (Match $match) {
                $match->setUserData($match . $match);
                return true;
            });

        // when
        $userData = $filtered
            ->forFirst(function (Match $match) {
                return $match->getUserData();
            })
            ->orThrow();

        // then
        $this->assertEquals('FirstFirst', $userData);
    }

    /**
     * @test
     */
    public function shouldPreserveUserData_map()
    {
        // given
        $filtered = pattern('[A-Z][a-z]+')
            ->match('First, Second, Third, Fourth, Fifth')
            ->filter(function (Match $match) {
                $match->setUserData($match . $match);
                return true;
            });

        // when
        $userData = $filtered->map(function (Match $match) {
            return $match->getUserData();
        });

        // then
        $this->assertEquals(['FirstFirst', 'SecondSecond', 'ThirdThird', 'FourthFourth', 'FifthFifth'], $userData);
    }
}

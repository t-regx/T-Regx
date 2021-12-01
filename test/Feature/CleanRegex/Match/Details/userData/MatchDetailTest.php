<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\userData;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

class MatchDetailTest extends TestCase
{

    /**
     * @test
     */
    public function shouldPreserveUserData_findFirst()
    {
        // given
        $filtered = pattern('[A-Z][a-z]+')
            ->match('First, Second, Third')
            ->remaining(function (Detail $detail) {
                $detail->setUserData($detail . $detail);
                return true;
            });

        // when
        $userData = $filtered
            ->findFirst(function (Detail $detail) {
                return $detail->getUserData();
            })
            ->orThrow();

        // then
        $this->assertSame('FirstFirst', $userData);
    }

    /**
     * @test
     */
    public function shouldPreserveUserData_map()
    {
        // given
        $userData = pattern('[A-Z][a-z]+')
            ->match('First, Second, Third, Fourth, Fifth')
            ->stream()
            ->filter(function (Detail $detail) {
                $detail->setUserData($detail . $detail);
                return true;
            })
            ->map(function (Detail $detail) {
                return $detail->getUserData();
            })
            ->all();

        // then
        $this->assertSame(['FirstFirst', 'SecondSecond', 'ThirdThird', 'FourthFourth', 'FifthFifth'], $userData);
    }
}

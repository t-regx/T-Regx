<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\userData;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Match;
use TRegx\CleanRegex\Match\FilteredMatchPattern;

class MatchTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->markTestSkipped("User data");
    }

    /**
     * @test
     */
    public function shouldPreserveUserData_first()
    {
        // given
        $filtered = $this->patternFilteredSettingUserData('First, Second, Third');

        // when
        $filtered->first(function (Match $match) {
            // then
            $this->assertEquals('FirstFirst', $match->getUserData());
        });
    }

    /**
     * @test
     */
    public function shouldPreserveUserData_map()
    {
        // given
        $filtered = $this->patternFilteredSettingUserData('First, Second, Third, Fourth, Fifth');

        // when
        $filtered->map(function (Match $match) {
            $userData = $match->getUserData();

            // then
            $allExpected = [
                0 => 'FirstFirst',
                1 => 'SecondSecond',
                2 => 'ThirdThird',
                3 => 'FourthFourth',
                4 => 'FifthFifth'
            ];
            $index = $match->index();
            $expected = $allExpected[$index];
            $this->assertEquals($expected, $userData);
        });
    }

    private function patternFilteredSettingUserData(string $subject): FilteredMatchPattern
    {
        return pattern('[A-Z][a-z]+')->match($subject)
            ->filter(function (Match $match) {
                $userData = str_repeat($match, 2);
                $match->setUserData($userData);
                return true;
            });
    }
}

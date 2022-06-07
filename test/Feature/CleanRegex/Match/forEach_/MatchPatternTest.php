<?php
namespace Test\Feature\CleanRegex\Match\forEach_;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use Test\Utils\Functions;
use Test\Utils\Structure\AssertsStructure;
use Test\Utils\Structure\Expect;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern
 */
class MatchPatternTest extends TestCase
{
    use TestCasePasses, AssertsDetail, AssertsStructure;

    /**
     * @test
     */
    public function shouldNotInvokeCallback_onUnmatched()
    {
        // given
        $match = Pattern::of('Nothing is true')->match('Everything is permited');
        // when
        $match->forEach(Functions::fail());
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldInvokeDetail()
    {
        // given
        $match = Pattern::of('B([io]fu|ombu)r')->match('Bifur, Bofur i Bombur');
        // when
        $match->forEach(Functions::collect($dwarves));
        // then
        $this->assertStructure($dwarves, [
            Expect::text('Bifur'),
            Expect::text('Bofur'),
            Expect::text('Bombur'),
        ]);
        $this->assertDetailsIndexed(...$dwarves);
        $this->assertDetailsSubject('Bifur, Bofur i Bombur', ...$dwarves);
        $this->assertDetailsAll(['Bifur', 'Bofur', 'Bombur'], ...$dwarves);
    }
}

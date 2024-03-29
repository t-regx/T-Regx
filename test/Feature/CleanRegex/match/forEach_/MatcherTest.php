<?php
namespace Test\Feature\CleanRegex\match\forEach_;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use Test\Utils\Functions;
use Test\Utils\Structure\AssertsStructure;
use Test\Utils\Structure\Expect;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\Matcher
 */
class MatcherTest extends TestCase
{
    use TestCasePasses, AssertsDetail, AssertsStructure;

    /**
     * @test
     */
    public function shouldNotInvokeCallback_onUnmatched()
    {
        // given
        $matcher = Pattern::of('Nothing is true')->match('Everything is permited');
        // when
        $matcher->forEach(Functions::fail());
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldInvokeDetail()
    {
        // given
        $matcher = Pattern::of('B([io]fu|ombu)r')->match('Bifur, Bofur i Bombur');
        // when
        $matcher->forEach(Functions::collect($dwarves));
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

<?php
namespace Test\Feature\CleanRegex\match\forEach_;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\Search
 */
class SearchTest extends TestCase
{
    use TestCasePasses;

    /**
     * @test
     */
    public function shouldNotInvokeCallback_onUnmatched()
    {
        // given
        $search = Pattern::of('Nothing is true')->search('Everything is permited');
        // when
        $search->forEach(Functions::fail());
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldInvokeDetail()
    {
        // given
        $search = Pattern::of('B([io]fu|ombu)r')->search('Bifur, Bofur i Bombur');
        // when
        $search->forEach(Functions::collect($dwarves));
        // then
        $this->assertSame(['Bifur', 'Bofur', 'Bombur'], $dwarves);
    }
}

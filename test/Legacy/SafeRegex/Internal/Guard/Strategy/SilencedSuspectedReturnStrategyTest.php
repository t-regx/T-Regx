<?php
namespace Test\Legacy\SafeRegex\Internal\Guard\Strategy;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\Internal\Guard\Strategy\SilencedSuspectedReturnStrategy;

/**
 * @deprecated
 * @covers \TRegx\SafeRegex\Internal\Guard\Strategy\SilencedSuspectedReturnStrategy
 */
class SilencedSuspectedReturnStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSilence()
    {
        // given
        $strategy = new SilencedSuspectedReturnStrategy();
        // when
        $isSuspected = $strategy->isSuspected('any string', null);
        // then
        $this->assertFalse($isSuspected);
    }
}

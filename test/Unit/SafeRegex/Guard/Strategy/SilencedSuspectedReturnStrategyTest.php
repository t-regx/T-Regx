<?php
namespace Test\Unit\TRegx\SafeRegex\Guard\Strategy;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\Guard\Strategy\SilencedSuspectedReturnStrategy;

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

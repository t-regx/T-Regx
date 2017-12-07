<?php

namespace Test\SafeRegex;

use PHPUnit\Framework\TestCase;
use SafeRegex\Guard\GuardedExecution;

class GuardedExecutionTest extends TestCase
{
    /**
     * @test
     */
    public function shouldInvoke()
    {
        // given
        @$this->pregWarning();

        // when
        $invocation = GuardedExecution::catch('preg_match', function () {
            return @preg_match('', '');
        });

        // then
        $this->assertNull($invocation->getException());
    }

    /**
     * @test
     */
    public function shouldInvokeAfterPregWarning()
    {
        // given


        // when


        // then

    }

    /**
     * @test
     */
    public function shouldInvokeAfterNonPregWarning()
    {
        // given
        @$this->phpWarning();


        // when


        // then

    }

    private function phpWarning()
    {
        foreach (2 as $foo) ;
    }

    private function pregWarning()
    {
        preg_match('', '');
    }
}

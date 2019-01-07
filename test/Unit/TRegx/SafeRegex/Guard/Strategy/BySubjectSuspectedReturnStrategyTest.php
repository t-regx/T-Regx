<?php
namespace Test\Unit\TRegx\SafeRegex\Guard\Strategy;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\Guard\Strategy\BySubjectSuspectedReturnStrategy;

class BySubjectSuspectedReturnStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotBeSuspected_emptyArrayInput_emptyArrayOutput()
    {
        // given
        $strategy = new BySubjectSuspectedReturnStrategy([]);

        // when
        $isSuspected = $strategy->isSuspected('', []);

        // then
        $this->assertFalse($isSuspected);
    }

    /**
     * @test
     */
    public function shouldNotBeSuspected_arrayInput_arrayOutput()
    {
        // given
        $strategy = new BySubjectSuspectedReturnStrategy([1]);

        // when
        $isSuspected = $strategy->isSuspected('', [2]);

        // then
        $this->assertFalse($isSuspected);
    }

    /**
     * @test
     */
    public function shouldNotBeSuspected_stringInput_stringOutput()
    {
        // given
        $strategy = new BySubjectSuspectedReturnStrategy('input');

        // when
        $isSuspected = $strategy->isSuspected('', 'output');

        // then
        $this->assertFalse($isSuspected);
    }

    /**
     * @test
     */
    public function shouldBeSuspected_arrayInput_emptyArrayOutput()
    {
        // given
        $strategy = new BySubjectSuspectedReturnStrategy(['input']);

        // when
        $isSuspected = $strategy->isSuspected('', []);

        // then
        $this->assertTrue($isSuspected);
    }

    /**
     * @test
     */
    public function shouldBeSuspected_stringInput_emptyArrayOutput()
    {
        // given
        $strategy = new BySubjectSuspectedReturnStrategy('input');

        // when
        $isSuspected = $strategy->isSuspected('', null);

        // then
        $this->assertTrue($isSuspected);
    }
}

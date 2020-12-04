<?php
namespace Test\Unit\TRegx\SafeRegex\Guard\Strategy;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\Guard\Strategy\PregFilterSuspectedReturnStrategy;

class PregFilterSuspectedReturnStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotBeSuspected_emptyArrayInput_emptyArrayOutput()
    {
        // given
        $strategy = new PregFilterSuspectedReturnStrategy([]);

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
        $strategy = new PregFilterSuspectedReturnStrategy([1]);

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
        $strategy = new PregFilterSuspectedReturnStrategy('input');

        // when
        $isSuspected = $strategy->isSuspected('', 'output');

        // then
        $this->assertFalse($isSuspected);
    }

    /**
     * @test
     */
    public function shouldNotBeSuspected_carefully_arrayInput_emptyArrayOutput()
    {
        /**
         * preg_filter() returns an empty array in two cases:
         *  - Input array was empty from the beginning
         *  - Input array was populated, but every element was filtered out
         * That's why, only by empty array as a result, it's IMPOSSIBLE to determine whether
         * preg_filter() failed or not. The only way is to capture the warning thrown by preg_filter(),
         * or it would be necessary to iterate the array and preg_match() all of them. That would, however,
         * be really inefficient.
         *
         * preg_replace() doesn't have this problem, because an empty array can only be
         * returned in the case of an empty array in the beginning.
         */

        // given
        $strategy = new PregFilterSuspectedReturnStrategy(['input']);

        // when
        $isSuspected = $strategy->isSuspected('', []);

        // then
        $this->assertFalse($isSuspected);
    }

    /**
     * @test
     */
    public function shouldBeSuspected_stringInput_emptyArrayOutput()
    {
        // given
        $strategy = new PregFilterSuspectedReturnStrategy('input');

        // when
        $isSuspected = $strategy->isSuspected('', null);

        // then
        $this->assertTrue($isSuspected);
    }
}

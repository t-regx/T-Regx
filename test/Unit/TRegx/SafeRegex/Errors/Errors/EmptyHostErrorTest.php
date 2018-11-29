<?php
namespace Test\Unit\TRegx\SafeRegex\Errors\Errors;

use TRegx\CleanRegex\Exception\CleanRegex\InternalCleanRegexException;
use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\Errors\Errors\EmptyHostError;

class EmptyHostErrorTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotOccur()
    {
        // given
        $error = new EmptyHostError();

        // when
        $occurred = $error->occurred();

        // then
        $this->assertFalse($occurred);
    }

    /**
     * @test
     */
    public function shouldThrow_onGetSafeRegexException()
    {
        // given
        $error = new EmptyHostError();

        // then
        $this->expectException(InternalCleanRegexException::class);

        // when
        $error->getSafeRegexpException('preg_replace');
    }
}

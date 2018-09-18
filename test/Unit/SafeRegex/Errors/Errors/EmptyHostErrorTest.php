<?php
namespace Test\Unit\SafeRegex\Errors\Errors;

use CleanRegex\Exception\CleanRegex\InternalCleanRegexException;
use PHPUnit\Framework\TestCase;
use SafeRegex\Errors\Errors\EmptyHostError;

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

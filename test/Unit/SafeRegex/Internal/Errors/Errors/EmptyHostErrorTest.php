<?php
namespace Test\Unit\SafeRegex\Internal\Errors\Errors;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\SafeRegex\Internal\Errors\Errors\EmptyHostError;

/**
 * @covers \TRegx\SafeRegex\Internal\Errors\Errors\EmptyHostError
 */
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
        $error->getSafeRegexpException('preg_replace', '/pattern/');
    }
}

<?php
namespace Test\Unit\TRegx\SafeRegex\Errors\Errors;

use PHPUnit\Framework\TestCase;
use Test\Warnings;
use TRegx\CleanRegex\Exception\CleanRegex\InternalCleanRegexException;
use TRegx\SafeRegex\Errors\Errors\RuntimeError;
use TRegx\SafeRegex\Errors\Errors\RuntimeErrorFactory;
use TRegx\SafeRegex\Exception\RuntimeSafeRegexException;

class RuntimeErrorTest extends TestCase
{
    use Warnings;

    /**
     * @test
     */
    public function shouldOccur()
    {
        // given
        $error = new RuntimeError(PREG_BACKTRACK_LIMIT_ERROR);

        // when
        $occurred = $error->occurred();

        // then
        $this->assertTrue($occurred);
    }

    /**
     * @test
     */
    public function shouldNotOccur()
    {
        // given
        $error = new RuntimeError(PREG_NO_ERROR);

        // when
        $occurred = $error->occurred();

        // then
        $this->assertFalse($occurred);
    }

    /**
     * @test
     */
    public function shouldClean()
    {
        // given
        $this->causeRuntimeWarning();
        $error = RuntimeErrorFactory::getLast();

        // when
        $error->clear();

        // then
        $this->assertFalse(RuntimeErrorFactory::getLast()->occurred());
    }

    /**
     * @test
     */
    public function shouldGetSafeRegexException()
    {
        // given
        $error = new RuntimeError(PREG_BAD_UTF8_ERROR);

        // when
        /** @var RuntimeSafeRegexException $exception */
        $exception = $error->getSafeRegexpException('preg_replace');

        // then
        $this->assertInstanceOf(RuntimeSafeRegexException::class, $exception);
        $this->assertEquals('preg_replace', $exception->getInvokingMethod());
        $this->assertEquals(PREG_BAD_UTF8_ERROR, $exception->getError());
        $this->assertEquals('PREG_BAD_UTF8_ERROR', $exception->getErrorName());
        $this->assertEquals('After invoking preg_replace(), preg_last_error() returned PREG_BAD_UTF8_ERROR.',
            $exception->getMessage());
    }

    /**
     * @test
     */
    public function shouldNotGetSafeRegexException()
    {
        // given
        $error = new RuntimeError(PREG_NO_ERROR);

        // then
        $this->expectException(InternalCleanRegexException::class);

        // when
        $error->getSafeRegexpException('preg_match');
    }
}

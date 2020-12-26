<?php
namespace Test\Functional\TRegx\SafeRegex\Errors\Errors;

use PHPUnit\Framework\TestCase;
use Test\Warnings;
use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\SafeRegex\Errors\Errors\RuntimeError;
use TRegx\SafeRegex\Errors\Errors\RuntimeErrorFactory;
use TRegx\SafeRegex\Exception\RuntimePregException;

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
        /** @var RuntimePregException $exception TODO Remove stupid type from PHP 7.4 */
        $exception = $error->getSafeRegexpException('preg_replace', '/pattern/');

        // then
        $this->assertInstanceOf(RuntimePregException::class, $exception);
        $this->assertSame('preg_replace', $exception->getInvokingMethod());
        $this->assertSame(PREG_BAD_UTF8_ERROR, $exception->getError());
        $this->assertSame('PREG_BAD_UTF8_ERROR', $exception->getErrorName());
        $this->assertSame('/pattern/', $exception->getPregPattern());
        $this->assertSame('After invoking preg_replace(), preg_last_error() returned PREG_BAD_UTF8_ERROR', $exception->getMessage());
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
        $error->getSafeRegexpException('preg_match', '/pattern/');
    }
}

<?php
namespace Test\Functional\TRegx\SafeRegex\Internal\Errors;

use PHPUnit\Framework\TestCase;
use Test\Warnings;
use TRegx\Exception\MalformedPatternException;
use TRegx\SafeRegex\Internal\Errors\Errors\BothHostError;
use TRegx\SafeRegex\Internal\Errors\Errors\CompileError;
use TRegx\SafeRegex\Internal\Errors\Errors\EmptyHostError;
use TRegx\SafeRegex\Internal\Errors\Errors\RuntimeError;
use TRegx\SafeRegex\Internal\Errors\ErrorsCleaner;

class ErrorsCleanerTest extends TestCase
{
    use Warnings;

    /**
     * @test
     */
    public function shouldGetRuntimeError()
    {
        // given
        $cleaner = new ErrorsCleaner();
        $this->causeRuntimeWarning();

        // when
        $error = $cleaner->getError();

        // then
        $this->assertInstanceOf(RuntimeError::class, $error);
        $this->assertTrue($error->occurred());

        // cleanup
        $error->clear();
    }

    /**
     * @test
     * @see https://bugs.php.net/bug.php?id=74183
     */
    public function shouldGetCompileError_Bug_Exists()
    {
        if ($this->isBugFixed()) {
            $this->markTestSkipped('After compile-time warning calling preg_match(), preg_last_error() still return PREG_NO_ERROR. Bug fixed in 7.1.13');
        }

        // given
        $cleaner = new ErrorsCleaner();
        $this->causeMalformedPatternWarning();

        // when
        $error = $cleaner->getError();

        // then
        $this->assertInstanceOf(CompileError::class, $error);
        $this->assertTrue($error->occurred());

        // cleanup
        $error->clear();
    }

    /**
     * @test
     * @see https://bugs.php.net/bug.php?id=74183
     */
    public function shouldGetCompileError_Bug_Fixed()
    {
        if (!$this->isBugFixed()) {
            $this->markTestSkipped("Bug fixed in 7.1.13, now compile-time warnings in preg_match() causes preg_last_error() ");
        }

        // given
        $cleaner = new ErrorsCleaner();
        $this->causeMalformedPatternWarning();

        // when
        $error = $cleaner->getError();

        // then
        $this->assertInstanceOf(BothHostError::class, $error);
        $this->assertTrue($error->occurred());

        // cleanup
        $error->clear();
    }

    private function isBugFixed(): bool
    {
        if (PHP_VERSION_ID === 70200) {
            return false;
        }
        return PHP_VERSION_ID >= 70113;
    }

    /**
     * @test
     */
    public function shouldGetBothHostError()
    {
        // given
        $cleaner = new ErrorsCleaner();
        $this->causeMalformedPatternWarning();
        $this->causeRuntimeWarning();

        // when
        $error = $cleaner->getError();

        // then
        $this->assertInstanceOf(BothHostError::class, $error);
        $this->assertTrue($error->occurred());

        // cleanup
        $error->clear();
    }

    /**
     * @test
     */
    public function shouldBothHostErrorGetSafeRegexException()
    {
        // given
        $cleaner = new ErrorsCleaner();
        $this->causeMalformedPatternWarning();
        $this->causeRuntimeWarning();
        $error = $cleaner->getError();

        // when
        $exception = $error->getSafeRegexpException('method_name', '/foo/');

        // then
        /** @var MalformedPatternException $exception */
        $this->assertInstanceOf(MalformedPatternException::class, $exception);
        $this->assertSame('method_name', $exception->getInvokingMethod());
        $this->assertSame('/foo/', $exception->getPregPattern());

        // cleanup
        $error->clear();
    }

    /**
     * @test
     */
    public function shouldGetEmptyHostError()
    {
        // given
        $cleaner = new ErrorsCleaner();

        // when
        $error = $cleaner->getError();

        // then
        $this->assertInstanceOf(EmptyHostError::class, $error);
        $this->assertFalse($error->occurred());

        // cleanup
        $error->clear();
    }

    /**
     * @test
     */
    public function shouldClearCompileError()
    {
        // given
        $cleaner = new ErrorsCleaner();
        $this->causeMalformedPatternWarning();

        // when
        $cleaner->clear();

        // then
        $error = error_get_last();
        $this->assertNull($error);
    }

    /**
     * @test
     */
    public function shouldClearRuntimeError()
    {
        // given
        $cleaner = new ErrorsCleaner();
        $this->causeRuntimeWarning();

        // when
        $cleaner->clear();

        // then
        $error = preg_last_error();
        $this->assertSame(PREG_NO_ERROR, $error);
    }
}

<?php
namespace Test\Functional\TRegx\SafeRegex\ExceptionFactory\preg_filter;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\Errors\ErrorsCleaner;
use TRegx\SafeRegex\Exception\SuspectedReturnSafeRegexException;
use TRegx\SafeRegex\ExceptionFactory;

class ExceptionFactoryTest extends TestCase
{
    protected function setUp()
    {
        $this->clearErrors();
    }

    /**
     * @test
     */
    public function shouldThrow_unmatched_string()
    {
        // given
        $result = @preg_filter('/[invalid/', 'replacement', '');
        $this->clearErrors();

        // when
        $exception = (new ExceptionFactory())->retrieveGlobals('preg_filter', $result);

        // then
        $this->assertInstanceOf(SuspectedReturnSafeRegexException::class, $exception);
        $this->assertEquals("Invoking preg_filter() resulted in 'NULL'.", $exception->getMessage());
    }

    /**
     * @test
     */
    public function shouldThrow_unmatched_array()
    {
        // given
        $result = preg_filter(['/pattern/u'], '', ["\xc3\x28"]);
        $this->clearErrors();

        // when
        $exception = (new ExceptionFactory())->retrieveGlobals('preg_filter', $result);

        // then
        $this->assertInstanceOf(SuspectedReturnSafeRegexException::class, $exception);
        $this->assertEquals("Invoking preg_filter() resulted in 'array (\n)'.", $exception->getMessage());
    }

    private function clearErrors(): void
    {
        (new ErrorsCleaner())->clear();
    }
}

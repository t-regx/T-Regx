<?php
namespace Test\Functional\TRegx\SafeRegex\ExceptionFactory;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\Errors\ErrorsCleaner;
use TRegx\SafeRegex\Exception\CompilePregException;
use TRegx\SafeRegex\Exception\RuntimePregException;
use TRegx\SafeRegex\Exception\SuspectedReturnPregException;
use TRegx\SafeRegex\ExceptionFactory;
use TRegx\SafeRegex\Guard\Strategy\DefaultSuspectedReturnStrategy;

class ExceptionFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        (new ErrorsCleaner())->clear();
    }

    /**
     * @dataProvider \Test\DataProviders::invalidPregPatterns()
     * @param string $invalidPattern
     */
    public function testCompileErrors(string $invalidPattern)
    {
        // given
        @preg_match($invalidPattern, '');
        $exceptionFactory = $this->create();

        // when
        $exception = $exceptionFactory->retrieveGlobals('preg_match', false);

        // then
        $this->assertInstanceOf(CompilePregException::class, $exception);
    }

    /**
     * @dataProvider \Test\DataProviders::invalidUtf8Sequences()
     * @param $description
     * @param $utf8
     */
    public function testRuntimeErrors(string $description, string $utf8)
    {
        // given
        @preg_match("/pattern/u", $utf8);
        $exceptionFactory = $this->create();

        // when
        $exception = $exceptionFactory->retrieveGlobals('preg_match', false);

        // then
        $this->assertInstanceOf(RuntimePregException::class, $exception);
    }

    public function testUnexpectedReturnError()
    {
        // given
        $result = false;
        $exceptionFactory = $this->create();

        // when
        $exception = $exceptionFactory->retrieveGlobals('preg_match', $result);

        // then
        $this->assertInstanceOf(SuspectedReturnPregException::class, $exception);
        $this->assertEquals("Invoking preg_match() resulted in 'false'.", $exception->getMessage());
    }

    private function create(): ExceptionFactory
    {
        return new ExceptionFactory(new DefaultSuspectedReturnStrategy(), new ErrorsCleaner());
    }
}

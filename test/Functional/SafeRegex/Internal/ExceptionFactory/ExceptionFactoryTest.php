<?php
namespace Test\Functional\TRegx\SafeRegex\Internal\ExceptionFactory;

use PHPUnit\Framework\TestCase;
use TRegx\Exception\MalformedPatternException;
use TRegx\SafeRegex\Exception\RuntimePregException;
use TRegx\SafeRegex\Exception\SuspectedReturnPregException;
use TRegx\SafeRegex\Internal\Errors\ErrorsCleaner;
use TRegx\SafeRegex\Internal\ExceptionFactory;
use TRegx\SafeRegex\Internal\Guard\Strategy\DefaultSuspectedReturnStrategy;

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
        $this->assertInstanceOf(MalformedPatternException::class, $exception);
        $this->assertSame('/pattern/', $exception->getPregPattern());
    }

    /**
     * @dataProvider \Test\DataProviders::invalidUtf8Sequences()
     * @param $utf8
     */
    public function testRuntimeErrors(string $utf8)
    {
        // given
        @preg_match("/pattern/u", $utf8);
        $exceptionFactory = $this->create();

        // when
        $exception = $exceptionFactory->retrieveGlobals('preg_match', false);

        // then
        $this->assertInstanceOf(RuntimePregException::class, $exception);
        $this->assertSame('/pattern/', $exception->getPregPattern());
    }

    public function testUnexpectedReturnError()
    {
        // given
        $exceptionFactory = $this->create();

        // when
        $exception = $exceptionFactory->retrieveGlobals('preg_match', false);

        // then
        $this->assertInstanceOf(SuspectedReturnPregException::class, $exception);
        $this->assertSame("Invoking preg_match() resulted in 'false'.", $exception->getMessage());
        $this->assertSame('/pattern/', $exception->getPregPattern());
    }

    private function create(): ExceptionFactory
    {
        return new ExceptionFactory('/pattern/', new DefaultSuspectedReturnStrategy(), new ErrorsCleaner());
    }
}

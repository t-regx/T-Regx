<?php
namespace Test\Structure\TRegx;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\PatternException;
use TRegx\RegexException;
use TRegx\SafeRegex\Exception\MalformedPatternException;
use TRegx\SafeRegex\Exception\PregException;
use TRegx\SafeRegex\Exception\PregMalformedPatternException;
use TRegx\SafeRegex\preg;

class HierarchyTest extends TestCase
{
    /**
     * @test
     */
    public function preg(): void
    {
        try {
            preg::match('/hello', 'word');
        } catch (\Throwable $exception) {
            $this->directInstanceOf(PregMalformedPatternException::class, $exception);
            $this->assertInstanceOf(MalformedPatternException::class, $exception);
            $this->assertInstanceOf(PregException::class, $exception);
            $this->assertInstanceOf(RegexException::class, $exception);

            $this->assertNotInstanceOf(PatternException::class, $exception);
        }
    }

    /**
     * @test
     */
    public function pattern(): void
    {
        try {
            pattern('(hello')->test('word');
        } catch (\Throwable $exception) {
            $this->directInstanceOf(PregMalformedPatternException::class, $exception);
            $this->assertInstanceOf(MalformedPatternException::class, $exception);
            $this->assertInstanceOf(PregException::class, $exception);
            $this->assertInstanceOf(RegexException::class, $exception);

            $this->assertNotInstanceOf(PatternException::class, $exception);
        }
    }

    private function directInstanceOf(string $expected, \Throwable $exception): void
    {
        // Don't use "instanceof", $exception must be this class exactly
        $this->assertEquals($expected, get_class($exception));
    }
}

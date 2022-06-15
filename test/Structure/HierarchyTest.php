<?php
namespace Test\Structure;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsHasClass;
use TRegx\CleanRegex\Exception\PatternException;
use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\Exception\MalformedPatternException;
use TRegx\Exception\RegexException;
use TRegx\SafeRegex\Exception\PregException;
use TRegx\SafeRegex\Exception\PregMalformedPatternException;
use TRegx\SafeRegex\preg;

class HierarchyTest extends TestCase
{
    use AssertsHasClass;

    /**
     * @test
     */
    public function preg(): void
    {
        try {
            preg::match('/hello', 'word');
        } catch (\Throwable $exception) {
            $this->assertHasClass(PregMalformedPatternException::class, $exception);
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
            $this->assertHasClass(PregMalformedPatternException::class, $exception);
            $this->assertInstanceOf(MalformedPatternException::class, $exception);
            $this->assertInstanceOf(PregException::class, $exception);
            $this->assertInstanceOf(RegexException::class, $exception);

            $this->assertNotInstanceOf(PatternException::class, $exception);
        }
    }

    /**
     * @test
     */
    public function pattern_trailing(): void
    {
        try {
            pattern('hello\\')->test('word');
        } catch (\Throwable $exception) {
            $this->assertHasClass(PatternMalformedPatternException::class, $exception);
            $this->assertInstanceOf(MalformedPatternException::class, $exception);
            $this->assertInstanceOf(PatternException::class, $exception);
            $this->assertInstanceOf(RegexException::class, $exception);

            $this->assertNotInstanceOf(PregException::class, $exception);
        }
    }
}

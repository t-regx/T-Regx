<?php
namespace Test\Unit\CleanRegex\Exception\Preg;

use CleanRegex\Exception\Preg\PatternMatchesException;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class PatternMatchesExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetMessage()
    {
        // given
        $exception = new PatternMatchesException(2);

        // when
        $message = $exception->getMessage();

        // then
        $this->assertEquals("Last error code: 2", $message);
    }

    /**
     * @test
     */
    public function shouldLastErrorBeVisibleInDebugger()
    {
        // given
        $exception = new PatternMatchesException(2);

        // when
        $class = new ReflectionClass(PatternMatchesException::class);
        $property = $class->getProperty('lastError');
        $property->setAccessible(true);
        $lastError = $property->getValue($exception);

        // then
        $this->assertEquals(2, $lastError);
    }
}

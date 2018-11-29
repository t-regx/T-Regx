<?php
namespace Test\Unit\TRegx\CleanRegex\Exception\Preg;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use TRegx\CleanRegex\Exception\Preg\PatternMatchesException;

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

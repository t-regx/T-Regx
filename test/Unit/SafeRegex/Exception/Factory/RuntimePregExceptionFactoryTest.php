<?php
namespace Test\Unit\TRegx\SafeRegex\Exception\Factory;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\Exception\CatastrophicBacktrackingPregException;
use TRegx\SafeRegex\Exception\Factory\RuntimePregExceptionFactory;
use TRegx\SafeRegex\Exception\JitStackLimitPregException;
use TRegx\SafeRegex\Exception\RecursionLimitPregException;
use TRegx\SafeRegex\Exception\RuntimePregException;
use TRegx\SafeRegex\Exception\SubjectEncodingPregException;
use TRegx\SafeRegex\Exception\Utf8OffsetPregException;

class RuntimePregExceptionFactoryTest extends TestCase
{
    /**
     * @test
     * @dataProvider errors
     * @param int $errorCode
     * @param string $name
     * @param string $className
     * @param string $message
     */
    public function shouldCreateException(int $errorCode, string $name, string $className, string $message)
    {
        // given
        $factory = new RuntimePregExceptionFactory('preg_method', '/pattern/', $errorCode);

        // when
        $exception = $factory->create();

        // then
        $this->assertInstanceOf($className, $exception);
        $this->assertEquals('preg_method', $exception->getInvokingMethod());
        $this->assertEquals($errorCode, $exception->getError());
        $this->assertEquals($name, $exception->getErrorName());
        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals('/pattern/', $exception->getPregPattern());
    }

    public function errors(): array
    {
        return [
            'PREG_NO_ERROR'              => [
                PREG_NO_ERROR,
                'PREG_NO_ERROR',
                RuntimePregException::class,
                "After invoking preg_method(), preg_last_error() returned PREG_NO_ERROR."
            ],
            'PREG_BAD_UTF8_ERROR'        => [
                PREG_BAD_UTF8_ERROR,
                'PREG_BAD_UTF8_ERROR',
                SubjectEncodingPregException::class,
                "After invoking preg_method(), preg_last_error() returned PREG_BAD_UTF8_ERROR."
            ],
            'PREG_INTERNAL_ERROR'        => [
                PREG_INTERNAL_ERROR,
                'PREG_INTERNAL_ERROR',
                RuntimePregException::class,
                "After invoking preg_method(), preg_last_error() returned PREG_INTERNAL_ERROR."
            ],
            'PREG_BACKTRACK_LIMIT_ERROR' => [
                PREG_BACKTRACK_LIMIT_ERROR,
                'PREG_BACKTRACK_LIMIT_ERROR',
                CatastrophicBacktrackingPregException::class,
                "After invoking preg_method(), preg_last_error() returned PREG_BACKTRACK_LIMIT_ERROR."
            ],
            'PREG_RECURSION_LIMIT_ERROR' => [
                PREG_RECURSION_LIMIT_ERROR,
                'PREG_RECURSION_LIMIT_ERROR',
                RecursionLimitPregException::class,
                "After invoking preg_method(), preg_last_error() returned PREG_RECURSION_LIMIT_ERROR."
            ],
            'PREG_BAD_UTF8_OFFSET_ERROR' => [
                PREG_BAD_UTF8_OFFSET_ERROR,
                'PREG_BAD_UTF8_OFFSET_ERROR',
                Utf8OffsetPregException::class,
                "Invalid UTF-8 offset parameter was passed to preg_method()."
            ],
            'PREG_JIT_STACKLIMIT_ERROR'  => [
                PREG_JIT_STACKLIMIT_ERROR,
                'PREG_JIT_STACKLIMIT_ERROR',
                JitStackLimitPregException::class,
                "After invoking preg_method(), preg_last_error() returned PREG_JIT_STACKLIMIT_ERROR."
            ],
        ];
    }
}

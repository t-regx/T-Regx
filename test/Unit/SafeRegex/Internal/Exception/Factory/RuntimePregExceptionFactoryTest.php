<?php
namespace Test\Unit\TRegx\SafeRegex\Internal\Exception\Factory;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\Exception\CatastrophicBacktrackingException;
use TRegx\SafeRegex\Exception\JitStackLimitException;
use TRegx\SafeRegex\Exception\RecursionException;
use TRegx\SafeRegex\Exception\RuntimePregException;
use TRegx\SafeRegex\Exception\SubjectEncodingException;
use TRegx\SafeRegex\Exception\UnicodeOffsetException;
use TRegx\SafeRegex\Internal\Exception\Factory\RuntimePregExceptionFactory;

/**
 * @covers \TRegx\SafeRegex\Internal\Exception\Factory\RuntimePregExceptionFactory
 */
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
        $this->assertSame('preg_method', $exception->getInvokingMethod());
        $this->assertSame($errorCode, $exception->getError());
        $this->assertSame($name, $exception->getErrorName());
        $this->assertSame($message, $exception->getMessage());
        $this->assertSame('/pattern/', $exception->getPregPattern());
    }

    public function errors(): array
    {
        return [
            'PREG_NO_ERROR'              => [
                PREG_NO_ERROR,
                'PREG_NO_ERROR',
                RuntimePregException::class,
                "After invoking preg_method(), preg_last_error() returned PREG_NO_ERROR"
            ],
            'PREG_BAD_UTF8_ERROR'        => [
                PREG_BAD_UTF8_ERROR,
                'PREG_BAD_UTF8_ERROR',
                SubjectEncodingException::class,
                "After invoking preg_method(), preg_last_error() returned PREG_BAD_UTF8_ERROR"
            ],
            'PREG_INTERNAL_ERROR'        => [
                PREG_INTERNAL_ERROR,
                'PREG_INTERNAL_ERROR',
                RuntimePregException::class,
                "After invoking preg_method(), preg_last_error() returned PREG_INTERNAL_ERROR"
            ],
            'PREG_BACKTRACK_LIMIT_ERROR' => [
                PREG_BACKTRACK_LIMIT_ERROR,
                'PREG_BACKTRACK_LIMIT_ERROR',
                CatastrophicBacktrackingException::class,
                "After invoking preg_method(), preg_last_error() returned PREG_BACKTRACK_LIMIT_ERROR"
            ],
            'PREG_RECURSION_LIMIT_ERROR' => [
                PREG_RECURSION_LIMIT_ERROR,
                'PREG_RECURSION_LIMIT_ERROR',
                RecursionException::class,
                "After invoking preg_method(), preg_last_error() returned PREG_RECURSION_LIMIT_ERROR"
            ],
            'PREG_BAD_UTF8_OFFSET_ERROR' => [
                PREG_BAD_UTF8_OFFSET_ERROR,
                'PREG_BAD_UTF8_OFFSET_ERROR',
                UnicodeOffsetException::class,
                "Invalid UTF-8 offset parameter was passed to preg_method()"
            ],
            'PREG_JIT_STACKLIMIT_ERROR'  => [
                PREG_JIT_STACKLIMIT_ERROR,
                'PREG_JIT_STACKLIMIT_ERROR',
                JitStackLimitException::class,
                "After invoking preg_method(), preg_last_error() returned PREG_JIT_STACKLIMIT_ERROR"
            ],
        ];
    }
}

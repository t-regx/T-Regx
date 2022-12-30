<?php
namespace Test\Feature\CleanRegex\_error_handler;

use PHPUnit\Framework\TestCase;
use Test\Utils\ErrorHandlerAssertion;
use Test\Utils\Functions;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldThrowMalformedPatternWithOverriddenErrorHandlerTest()
    {
        // given
        $pattern = Pattern::of('+');
        \set_error_handler(Functions::ignore());
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        try {
            $pattern->test('foo');
        } finally {
            // clean
            \restore_error_handler();
        }
    }

    /**
     * @test
     */
    public function shouldThrowMalformedPatternWithOverriddenErrorHandlerReplace()
    {
        // given
        $pattern = Pattern::of('+');
        \set_error_handler(Functions::ignore());
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        try {
            $pattern->prune('foo');
        } finally {
            // clean
            \restore_error_handler();
        }
    }

    /**
     * @test
     */
    public function shouldRestoreErrorHandlerAfterException()
    {
        // given
        $replace = Pattern::of('value')->replace('value');
        $errorHandler = new ErrorHandlerAssertion();
        // when
        try {
            $replace->callback(Functions::throws(new \RuntimeException()));
        } catch (\RuntimeException $ignored) {
        }
        // then
        $errorHandler->assertOperational();
    }
}

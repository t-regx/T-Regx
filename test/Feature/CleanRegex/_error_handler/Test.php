<?php
namespace Test\Feature\CleanRegex\_error_handler;

use PHPUnit\Framework\TestCase;
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
        $pattern->test('foo');
        // clean
        \restore_error_handler();
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
        $pattern->prune('foo');
        // clean
        \restore_error_handler();
    }
}

<?php
namespace Test\Feature\CleanRegex\_jit;

use PHPUnit\Framework\TestCase;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;
use TRegx\Pcre;

class Test extends TestCase
{
    use TestCasePasses;

    /**
     * @test
     */
    public function shouldAcceptNoJustInTimeCompiler()
    {
        // given
        $pattern = Pattern::of('(*NO_JIT)foo');
        // then
        if (Pcre::pcre2()) {
            $this->pass();
        } else {
            $this->expectException(MalformedPatternException::class);
            $this->expectExceptionMessage('(*VERB) not recognized or malformed at offset 4');
        }
        // when
        $pattern->test('foo');
    }
}

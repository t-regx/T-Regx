<?php
namespace Test\Feature\TRegx\CleanRegex\_prepared\inject\posix;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsPattern;
use TRegx\CleanRegex\Pattern;

class PatternTest extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     */
    public function shouldInjectIntoImmediatelyClosedPosixCharacter()
    {
        // when
        $pattern = Pattern::inject('[]@]', []);

        // then
        $this->assertSamePattern('/[]@]/', $pattern);
    }

    /**
     * @test
     */
    public function shouldInjectIntoImmediatelyClosedPosixCharacterTest()
    {
        // when
        $pattern = Pattern::inject('^[]@]{2}$', []);

        // then
        $this->assertTrue($pattern->test(']@'));
    }
}

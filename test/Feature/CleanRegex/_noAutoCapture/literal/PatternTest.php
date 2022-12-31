<?php
namespace Test\Feature\CleanRegex\_noAutoCapture\literal;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsPattern;
use TRegx\CleanRegex\Pattern;
use TRegx\Pcre;

class PatternTest extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     */
    public function shouldAcceptNoAutoCapture()
    {
        // when
        $pattern = Pattern::literal('Foo {}', 'n');
        // then
        $this->assertConsumesFirst('Foo {}', $pattern);
        if (PHP_VERSION_ID >= 80200) {
            $this->assertPatternIs('/Foo\ \{\}/n', $pattern);
        } else if (Pcre::pcre2()) {
            $this->assertPatternIs('/(?n)Foo\ \{\}/', $pattern);
        } else {
            $this->assertPatternIs('/Foo\ \{\}/', $pattern);
        }
    }
}

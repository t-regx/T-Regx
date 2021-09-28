<?php
namespace Test\Unit\TRegx\CleanRegex\Composite\CompositePattern\failAny;

use PHPUnit\Framework\TestCase;
use Test\Utils\Definitions;
use TRegx\CleanRegex\Composite\CompositePattern;

/**
 * @covers \TRegx\CleanRegex\Composite\CompositePattern::testAll
 */
class CompositePatternTest extends TestCase
{
    /**
     * @test
     */
    public function testAllPassingPatterns()
    {
        // given
        $pattern = new CompositePattern([
            Definitions::pcre('/^P/'),
            Definitions::pcre('/R/'),
            Definitions::pcre('/E$/')
        ]);

        // when
        $fail = $pattern->failAny('PRE');

        // then
        $this->assertFalse($fail);
    }

    /**
     * @test
     */
    public function testOneFailingPattern()
    {
        // given
        $pattern = new CompositePattern([
            Definitions::pcre('/^P$/'),
            Definitions::pcre('/R/'),
            Definitions::pcre('/E/'),
            Definitions::pcre('/x/')
        ]);

        // when
        $fail = $pattern->failAny('PRE');

        // then
        $this->assertTrue($fail);
    }

    /**
     * @test
     */
    public function testAllFailingPatterns()
    {
        // given
        $pattern = new CompositePattern([
            Definitions::pcre('/1/'),
            Definitions::pcre('/2/'),
            Definitions::pcre('/3/'),
            Definitions::pcre('/4/')
        ]);

        // when
        $fail = $pattern->failAny('PRE');

        // then
        $this->assertTrue($fail);
    }
}

<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use AssertionError;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Definitions;
use TRegx\CleanRegex\Internal\Expression\Pcre;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Internal\Definitions
 */
class DefinitionsTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCreate()
    {
        // given
        $inputPatterns = [
            '[a-z]',
            new Pattern(new Pcre('/Foo/')),
        ];

        // when
        $patterns = Definitions::composed($inputPatterns, Functions::constant(new Definition('/bar/', '/car/')));

        // then
        $expected = [
            new Definition('/[a-z]/', '[a-z]'),
            new Definition('/bar/', '/car/'),
        ];
        $this->assertEquals($expected, $patterns);
    }

    /**
     * @test
     */
    public function shouldThrow_onInvalidPattern()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("CompositePattern only accepts type Pattern or string, but stdClass given");

        // when
        Definitions::composed([new \stdClass()], Functions::throws(new AssertionError()));
    }

    /**
     * @test
     */
    public function shouldThrow_string_onTrailingBackslash()
    {
        // given
        $patterns = [
            'pattern',
            'pattern\\'
        ];

        // then
        $this->expectException(PatternMalformedPatternException::class);
        $this->expectExceptionMessage('Pattern may not end with a trailing backslash');

        // when
        Definitions::composed($patterns, Functions::throws(new AssertionError()));
    }
}

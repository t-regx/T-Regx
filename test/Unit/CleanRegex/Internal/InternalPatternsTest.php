<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use AssertionError;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\Internal;
use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\InternalPatterns;
use TRegx\CleanRegex\Pattern;

class InternalPatternsTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCreate()
    {
        // given
        $inputPatterns = [
            '[a-z]',
            new Pattern(Internal::pcre('/foo/')),
        ];

        // when
        $patterns = InternalPatterns::compose($inputPatterns, Functions::constant(new Definition('/bar/', '/car/')));

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
        InternalPatterns::compose([new \stdClass()], Functions::throws(new AssertionError()));
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
        InternalPatterns::compose($patterns, Functions::throws(new AssertionError()));
    }
}

<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Expression;

use PHPUnit\Framework\TestCase;
use Test\Utils\Impl\ConstantFigures;
use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Prepared\Expression\Template;
use TRegx\CleanRegex\Internal\Prepared\Figure\InjectFigures;
use TRegx\CleanRegex\Internal\Prepared\Orthography\PcreOrthography;
use TRegx\CleanRegex\Internal\Prepared\Orthography\StandardOrthography;

class TemplateTest extends TestCase
{
    /**
     * @test
     */
    public function test()
    {
        // given
        $interpretation = new Template(new PcreOrthography('/foo:@/'), ConstantFigures::literal('bar{}'));

        // when
        $definition = $interpretation->definition();

        // then
        $this->assertEquals(new InternalPattern('/foo:bar\{\}/', '/foo:@/'), $definition);
    }

    /**
     * @test
     */
    public function shouldQuoteUsingDelimiter()
    {
        // given
        $interpretation = new Template(new PcreOrthography('%foo:@%m'), ConstantFigures::literal('bar%cat'));

        // when
        $definition = $interpretation->definition();

        // then
        $this->assertEquals(new InternalPattern('%foo:bar\%cat%m', '%foo:@%m'), $definition);
    }

    /**
     * @test
     */
    public function shouldThrowForTrailingBackslash()
    {
        // given
        $interpretation = new Template(new StandardOrthography('cat\\', 'x'), new InjectFigures([]));

        // then
        $this->expectException(PatternMalformedPatternException::class);
        $this->expectExceptionMessage('Pattern may not end with a trailing backslash');

        // when
        $interpretation->definition();
    }

    /**
     * @test
     */
    public function shouldNotUseDuplicateFlags()
    {
        // given
        $interpretation = new Template(new StandardOrthography('cat', 'xx'), new InjectFigures([]));

        // when
        $definition = $interpretation->definition();

        // then
        $this->assertEquals(new InternalPattern('/cat/x', 'cat'), $definition);
    }

    /**
     * @test
     */
    public function shouldInjectInCommentWithoutExtendedMode()
    {
        // given
        $interpretation = new Template(new StandardOrthography("/#@\n", 'i'), ConstantFigures::literal('cat%'));

        // when
        $definition = $interpretation->definition();

        // then
        $this->assertEquals(new InternalPattern("%/#cat\%\n%i", "/#@\n"), $definition);
    }

    /**
     * @test
     */
    public function shouldNotInjectPlaceholderInCommentExtendedMode()
    {
        // given
        $interpretation = new Template(new StandardOrthography('#@', 'x'), new ConstantFigures(0));

        // when
        $definition = $interpretation->definition();

        // then
        $this->assertEquals(new InternalPattern('/#@/x', '#@'), $definition);
    }
}

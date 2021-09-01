<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Expression;

use PHPUnit\Framework\TestCase;
use Test\Utils\Impl\ConstantFigures;
use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Prepared\Expression\Template;
use TRegx\CleanRegex\Internal\Prepared\Figure\InjectFigures;
use TRegx\CleanRegex\Internal\Prepared\Orthography\PcreOrthography;
use TRegx\CleanRegex\Internal\Prepared\Orthography\StandardOrthography;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\Expression\Template
 */
class TemplateTest extends TestCase
{
    /**
     * @test
     */
    public function test()
    {
        // given
        $template = new Template(new PcreOrthography('/foo:@/'), ConstantFigures::literal('bar{}'));

        // when
        $definition = $template->definition();

        // then
        $this->assertEquals(new Definition('/foo:bar\{\}/', '/foo:@/'), $definition);
    }

    /**
     * @test
     */
    public function shouldQuoteUsingDelimiter()
    {
        // given
        $template = new Template(new PcreOrthography('%foo:@%m'), ConstantFigures::literal('bar%cat'));

        // when
        $definition = $template->definition();

        // then
        $this->assertEquals(new Definition('%foo:bar\%cat%m', '%foo:@%m'), $definition);
    }

    /**
     * @test
     */
    public function shouldThrowForTrailingBackslash()
    {
        // given
        $template = new Template(new StandardOrthography('cat\\', 'x'), new InjectFigures([]));

        // then
        $this->expectException(PatternMalformedPatternException::class);
        $this->expectExceptionMessage('Pattern may not end with a trailing backslash');

        // when
        $template->definition();
    }

    /**
     * @test
     */
    public function shouldNotUseDuplicateFlags()
    {
        // given
        $template = new Template(new StandardOrthography('cat', 'xx'), new InjectFigures([]));

        // when
        $definition = $template->definition();

        // then
        $this->assertEquals(new Definition('/cat/x', 'cat'), $definition);
    }

    /**
     * @test
     */
    public function shouldInjectInCommentWithoutExtendedMode()
    {
        // given
        $template = new Template(new StandardOrthography("/#@\n", 'i'), ConstantFigures::literal('cat%'));

        // when
        $definition = $template->definition();

        // then
        $this->assertEquals(new Definition("%/#cat\%\n%i", "/#@\n"), $definition);
    }

    /**
     * @test
     */
    public function shouldNotInjectPlaceholderInCommentExtendedMode()
    {
        // given
        $template = new Template(new StandardOrthography('#@', 'x'), new ConstantFigures(0));

        // when
        $definition = $template->definition();

        // then
        $this->assertEquals(new Definition('/#@/x', '#@'), $definition);
    }
}

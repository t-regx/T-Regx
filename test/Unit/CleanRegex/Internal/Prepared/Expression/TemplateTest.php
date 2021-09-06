<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Expression;

use PHPUnit\Framework\TestCase;
use Test\Utils\Impl\ConstantFigures;
use Test\Utils\Impl\EqualsCondition;
use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Prepared\Expression\Template;
use TRegx\CleanRegex\Internal\Prepared\Figure\InjectFigures;
use TRegx\CleanRegex\Internal\Prepared\Orthography\PcreSpelling;
use TRegx\CleanRegex\Internal\Prepared\Orthography\StandardSpelling;

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
        $template = new Template(new PcreSpelling('/foo:@/'), ConstantFigures::literal('bar{}'));

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
        $template = new Template(new PcreSpelling('%foo:@%m'), ConstantFigures::literal('bar%cat'));

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
        $template = new Template(new StandardSpelling('cat\\', 'x', new EqualsCondition('/')), new InjectFigures([]));

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
        $template = new Template(new StandardSpelling('cat', 'xx', new EqualsCondition('/')), new InjectFigures([]));

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
        $template = new Template(new StandardSpelling("/#@\n", 'i', new EqualsCondition('~')), ConstantFigures::literal('cat~'));

        // when
        $definition = $template->definition();

        // then
        $this->assertEquals(new Definition("~/#cat\~\n~i", "/#@\n"), $definition);
    }

    /**
     * @test
     */
    public function shouldNotInjectPlaceholderInCommentExtendedMode()
    {
        // given
        $template = new Template(new StandardSpelling('#@', 'x', new EqualsCondition('/')), new ConstantFigures(0));

        // when
        $definition = $template->definition();

        // then
        $this->assertEquals(new Definition('/#@/x', '#@'), $definition);
    }
}

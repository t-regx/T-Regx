<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Expression;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\EqualsCondition;
use Test\Fakes\CleanRegex\Internal\Prepared\Figure\ConstantFigures;
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
        $predefinition = $template->predefinition();

        // then
        $this->assertEquals(new Definition('/foo:bar\{\}/', '/foo:@/'), $predefinition->definition());
    }

    /**
     * @test
     */
    public function shouldQuoteUsingDelimiter()
    {
        // given
        $template = new Template(new PcreSpelling('%foo:@%m'), ConstantFigures::literal('bar%cat'));

        // when
        $predefinition = $template->predefinition();

        // then
        $this->assertEquals(new Definition('%foo:bar\%cat%m', '%foo:@%m'), $predefinition->definition());
    }

    /**
     * @test
     */
    public function shouldNotUseDuplicateFlags()
    {
        // given
        $template = new Template(new StandardSpelling('cat', 'xx', new EqualsCondition('/')), new InjectFigures([]));

        // when
        $predefinition = $template->predefinition();

        // then
        $this->assertEquals(new Definition('/cat/x', 'cat'), $predefinition->definition());
    }

    /**
     * @test
     */
    public function shouldInjectInCommentWithoutExtendedMode()
    {
        // given
        $template = new Template(new StandardSpelling("/#@\n", 'i', new EqualsCondition('~')), ConstantFigures::literal('cat~'));

        // when
        $predefinition = $template->predefinition();

        // then
        $this->assertEquals(new Definition("~/#cat\~\n~i", "/#@\n"), $predefinition->definition());
    }

    /**
     * @test
     */
    public function shouldNotInjectPlaceholderInCommentExtendedMode()
    {
        // given
        $template = new Template(new StandardSpelling('#@', 'x', new EqualsCondition('/')), new ConstantFigures(0));

        // when
        $predefinition = $template->predefinition();

        // then
        $this->assertEquals(new Definition('/#@/x', '#@'), $predefinition->definition());
    }
}

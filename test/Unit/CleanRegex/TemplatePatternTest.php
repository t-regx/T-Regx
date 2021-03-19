<?php
namespace Test\Unit\TRegx\CleanRegex;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\TemplateFormatException;
use TRegx\CleanRegex\TemplatePattern;

class TemplatePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldFormat_throwInsufficient(): void
    {
        // given
        $template = new TemplatePattern('foo:&&', '', false);

        // then
        $this->expectException(TemplateFormatException::class);
        $this->expectExceptionMessage('There are 2 & tokens in template, but only 1 builder methods were used');

        // when
        $template->mask('hey', []);
    }

    /**
     * @test
     */
    public function shouldFormat_throwSuperfluous(): void
    {
        // given
        $template = new TemplatePattern('', '', false);

        // then
        $this->expectException(TemplateFormatException::class);
        $this->expectExceptionMessage('There are only 0 & tokens in template, but 1 builder methods were used');

        // when
        $template->mask('hey', []);
    }

    /**
     * @test
     */
    public function shouldInject_throwForInvalidFormat(): void
    {
        // given
        $template = new TemplatePattern('@&', '', false);

        // then
        $this->expectException(TemplateFormatException::class);
        $this->expectExceptionMessage('There are 1 & tokens in template, but only 0 builder methods were used');

        // when
        $template->inject(['bar']);
    }

    /**
     * @test
     */
    public function shouldBind_throwForInvalidFormat(): void
    {
        // given
        $template = new TemplatePattern('@foo&', '', false);

        // then
        $this->expectException(TemplateFormatException::class);
        $this->expectExceptionMessage('There are 1 & tokens in template, but only 0 builder methods were used');

        // when
        $template->bind(['foo' => 'bar']);
    }
}

<?php
namespace Test\Unit\TRegx\CleanRegex;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\TemplateFormatException;
use TRegx\CleanRegex\Internal\Prepared\Template\LiteralToken;
use TRegx\CleanRegex\Internal\Prepared\Template\MaskToken;
use TRegx\CleanRegex\TemplateBuilder;

class TemplateBuilderTest extends TestCase
{
    /**
     * @test
     * @dataProvider methods
     * @param string $method
     * @param array $arguments
     */
    public function shouldThrowForOverflowingLiteral(string $method, array $arguments): void
    {
        // given
        $template = new TemplateBuilder('^&&$', '', false, [new LiteralToken(), new LiteralToken(), new LiteralToken()]);

        // then
        $this->expectException(TemplateFormatException::class);
        $this->expectExceptionMessage('There are only 2 & tokens in template, but 3 builder methods were used');

        // when
        $template->$method(...$arguments);
    }

    /**
     * @test
     * @dataProvider methods
     * @param string $method
     * @param array $arguments
     */
    public function shouldThrowForMissingLiteral(string $method, array $arguments): void
    {
        // given
        $template = new TemplateBuilder('^&&$', '', false, [new LiteralToken()]);

        // then
        $this->expectException(TemplateFormatException::class);
        $this->expectExceptionMessage('There are 2 & tokens in template, but only 1 builder methods were used');

        // when
        $template->$method(...$arguments);
    }

    public function methods(): array
    {
        return [
            ['build', []],
            ['bind', [[]]],
            ['inject', [[]]],
        ];
    }

    /**
     * @test
     */
    public function shouldBuildBeImmutable(): void
    {
        // given
        $template = new TemplateBuilder('^&&$', 's', false, [new LiteralToken()]);

        // when
        $first = $template->putLiteral();
        $second = $template->putLiteral();
        $third = $template->putLiteral();

        // then
        $this->assertSame('/^&&$/s', $first->build()->delimited());
        $this->assertSame('/^&&$/s', $second->build()->delimited());
        $this->assertSame('/^&&$/s', $third->build()->delimited());
    }

    /**
     * @test
     */
    public function shouldChoseDelimiter(): void
    {
        // given
        $template = new TemplateBuilder('^&/$', '', false, [new LiteralToken()]);

        // when
        $pattern = $template->build();

        // then
        $this->assertSame('#^&/$#', $pattern->delimited());
    }

    /**
     * @test
     */
    public function shouldQuoteTokenWithDelimiter(): void
    {
        // given
        $template = new TemplateBuilder('^&#/$', '', false, [new MaskToken('`%`', [])]);

        // when
        $pattern = $template->build();

        // then
        $this->assertSame('%^`\%`#/$%', $pattern->delimited());
    }
}

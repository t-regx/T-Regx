<?php
namespace Test\Unit\TRegx\CleanRegex;

use PHPUnit\Framework\TestCase;
use Test\Utils\RawToken;
use Test\Utils\ThrowToken;
use TRegx\CleanRegex\Exception\TemplateFormatException;
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
        $template = new TemplateBuilder('^&&$', '', false, [new ThrowToken(), new ThrowToken(), new ThrowToken()]);

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
        $template = new TemplateBuilder('^&&$', '', false, [new ThrowToken()]);

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
        $template = new TemplateBuilder('^&&$', 's', false, [new RawToken('Z', '/')]);

        // when
        $first = $template->putLiteral('A');
        $second = $template->putLiteral('B');
        $third = $template->putLiteral('C');

        // then
        $this->assertSame('/^ZA$/s', $first->build()->delimited());
        $this->assertSame('/^ZB$/s', $second->build()->delimited());
        $this->assertSame('/^ZC$/s', $third->build()->delimited());
    }

    /**
     * @test
     */
    public function shouldBuild(): void
    {
        // given
        $template = new TemplateBuilder('^&&$', 's', false, [new RawToken('X', '/')]);

        // when
        $first = $template->putLiteral('{hi}');

        // then
        $this->assertSame('/^X\{hi\}$/s', $first->build()->delimited());
    }

    /**
     * @test
     */
    public function shouldChoseDelimiter(): void
    {
        // given
        $template = new TemplateBuilder('^&/$', '', false, [new RawToken('Y', '#')]);

        // when
        $pattern = $template->build();

        // then
        $this->assertSame('#^Y/$#', $pattern->delimited());
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

<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use Regex\SyntaxException;
use function Test\Fixture\Functions\catching;

class _modifiers extends TestCase
{
    /**
     * @test
     */
    public function caseSensitive(): void
    {
        $pattern = new Pattern('foo');
        $this->assertFalse($pattern->test('FOO'));
    }

    /**
     * @test
     */
    public function caseInsensitive(): void
    {
        $pattern = new Pattern('foo', Pattern::IGNORE_CASE);
        $this->assertTrue($pattern->test('FOO'));
    }

    /**
     * @test
     */
    public function nonMultiline(): void
    {
        $pattern = new Pattern('^line$');
        $this->assertFalse($pattern->test("line\nline"));
    }

    /**
     * @test
     */
    public function multiline(): void
    {
        $pattern = new Pattern('^line$', Pattern::MULTILINE);
        $this->assertTrue($pattern->test("line\nline"));
    }

    /**
     * @test
     */
    public function ascii(): void
    {
        $pattern = new Pattern('.');
        $this->assertSame(
            [chr(226), chr(130), chr(172)],
            $pattern->search('€'));
    }

    /**
     * @test
     */
    public function unicode(): void
    {
        $pattern = new Pattern('.', Pattern::UNICODE);
        $this->assertSame(['€'], $pattern->search('€'));
    }

    /**
     * @test
     */
    public function nonSingleline(): void
    {
        $pattern = new Pattern('.');
        $this->assertFalse($pattern->test("\n"));
    }

    /**
     * @test
     */
    public function singleline(): void
    {
        $pattern = new Pattern('.', Pattern::SINGLELINE);
        $this->assertTrue($pattern->test("\n"));
    }

    /**
     * @test
     */
    public function whitespace(): void
    {
        $pattern = new Pattern(' #');
        $this->assertTrue($pattern->test(' #'));
    }

    /**
     * @test
     */
    public function whitespaceIgnored(): void
    {
        $pattern = new Pattern("car #foo\n pet", Pattern::COMMENTS_WHITESPACE);
        $this->assertTrue($pattern->test('carpet'));
    }

    /**
     * @test
     */
    public function nonDuplicateNames()
    {
        catching(fn() => new Pattern('(?<group>Not) (?<group>today)'))
            ->assertException(SyntaxException::class)
            ->assertMessage('Two named subpatterns have the same name at offset 23.');
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function duplicateNames()
    {
        new Pattern('(?<group>Not) (?<group>today)', Pattern::DUPLICATE_NAMES);
    }

    /**
     * @test
     */
    public function greedyStandard(): void
    {
        $pattern = new Pattern('.+');
        $this->assertSame(['Foo'], $pattern->search('Foo'));
    }

    /**
     * @test
     */
    public function greedyInverted(): void
    {
        $pattern = new Pattern('.+', Pattern::INVERTED_GREEDY);
        $this->assertSame(['F', 'o', 'o'], $pattern->search('Foo'));
    }
}

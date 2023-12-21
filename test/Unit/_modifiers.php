<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Regex\ModifierException;
use Regex\Pattern;
use Regex\SyntaxException;
use TRegx\PhpUnit\DataProviders\DataProvider;
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
            ->assertMessageStartsWith('Two named subpatterns have the same name');
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

    /**
     * @test
     */
    public function nonAnchored(): void
    {
        $pattern = new Pattern('Foo');
        $this->assertSame(['Foo', 'Foo', 'Foo'], $pattern->search('FooFoo Foo'));
    }

    /**
     * @test
     */
    public function anchored(): void
    {
        $pattern = new Pattern('Foo', Pattern::ANCHORED);
        $this->assertSame(['Foo', 'Foo'], $pattern->search('FooFoo Foo'));
    }

    /**
     * @test
     */
    public function implicitCapture(): void
    {
        $pattern = new Pattern('(Foo)');
        $this->assertSame([null], $pattern->groupNames());
    }

    /**
     * @test
     */
    public function explicitCapture(): void
    {
        $pattern = new Pattern('(Car)(?<group>Pet)', Pattern::EXPLICIT_CAPTURE);
        $this->assertSame(['group'], $pattern->groupNames());
    }

    /**
     * @test
     * @dataProvider invalidModifiers
     */
    public function invalid(string $modifiers)
    {
        catching(fn() => new Pattern('\w+', $modifiers))
            ->assertException(ModifierException::class)
            ->assertMessage("Supplied one or more unexpected modifiers: '$modifiers'.");
    }

    public function invalidModifiers(): DataProvider
    {
        return DataProvider::list('k', 'kk', 'g', 'Z', 'G', \chr(2), 'mnp');
    }

    /**
     * @test
     */
    public function restrictiveEscape()
    {
        catching(fn() => new Pattern('\w+', 'mXi'))
            ->assertException(ModifierException::class)
            ->assertMessage("Supplied one or more unexpected modifiers: 'mXi', modifier 'X' is already applied.");
    }

    /**
     * @test
     */
    public function dollarEndOnly()
    {
        catching(fn() => new Pattern('\w+', 'mDi'))
            ->assertException(ModifierException::class)
            ->assertMessage("Supplied one or more unexpected modifiers: 'mDi', modifier 'D' is already applied.");
    }
}

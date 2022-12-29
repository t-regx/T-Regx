<?php
namespace Test\Feature\CleanRegex\_extended\_comments;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsPattern;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Pattern;

class PatternTest extends TestCase
{
    use AssertsPattern, TestCasePasses;

    /**
     * @test
     */
    public function shouldAddComment()
    {
        // when
        $pattern = Pattern::inject("You/her #@\n her?", [], 'ix');
        // then
        $this->assertPatternIs("%You/her #@\n her?%ix", $pattern);
    }

    /**
     * @test
     * @depends shouldAddComment
     */
    public function shouldAddComment_CaseSensitive()
    {
        // when
        $pattern = Pattern::inject("You/her #@\n her?", ['Bar'], 'X');
        // then
        $this->assertPatternIs("%You/her #(?>Bar)\n her?%X", $pattern);
    }

    /**
     * @test
     */
    public function shouldAddComment_SetExtended()
    {
        // when
        $pattern = Pattern::inject("(?x)You/her #@\n her?", []);
        // then
        $this->assertPatternIs("%(?x)You/her #@\n her?%", $pattern);
    }

    /**
     * @test
     * @depends shouldAddComment_SetExtended
     */
    public function shouldAddComment_SetExtended_CaseSensitive()
    {
        // when
        $pattern = Pattern::inject("(?X)You/her #@\n her?", ['Bar']);
        // then
        $this->assertPatternIs("%(?X)You/her #(?>Bar)\n her?%", $pattern);
    }

    /**
     * @test
     * @dataProvider unsetExtendedFlag
     * @depends      shouldAddComment
     * @depends      shouldAddComment_SetExtended
     */
    public function shouldUsePlaceholderInComment_UnsetExtended(string $unsetFlag, string $delimited)
    {
        // when
        $pattern = Pattern::inject($unsetFlag, ['Foo'], 'x');
        // then
        $this->assertConsumesFirst("#Foo\n", $pattern);
        $this->assertPatternIs($delimited, $pattern);
    }

    public function unsetExtendedFlag(): array
    {
        return [
            'unset flag'        => ["(?-x)#@\n", "/(?-x)#(?>Foo)\n/x"],
            'unset flag, short' => ["(?-x:#@\n)", "/(?-x:#(?>Foo)\n)/x"]
        ];
    }

    /**
     * @test
     */
    public function shouldAddComment_ManyFlagsExtendedNotFirst()
    {
        // when
        Pattern::inject("(?Ux)#@\n", []);
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldFailOnConstructiveSubpatternDestructiveSubpattern()
    {
        // given
        $pattern = Pattern::inject('(?x:(?-x:#boo:@))', ['value']);
        // then
        $this->assertConsumesFirst('#boo:value', $pattern);
    }

    /**
     * @test
     */
    public function shouldConsumeOnConstructiveSubpatternDestructiveSubpatternClosed()
    {
        // given
        $pattern = Pattern::inject("(?x:(?-x:)foo#@\n,bar)", []);
        // then
        $this->assertConsumesFirst('foo,bar', $pattern);
    }

    /**
     * @test
     */
    public function shouldParseCommentOnExtended()
    {
        // given
        $pattern = Pattern::inject("foo#boo:@\n,end", [], 'x');
        // then
        $this->assertConsumesFirst('foo,end', $pattern);
    }

    /**
     * @test
     */
    public function shouldFailOnConstructivePatternDestructiveSubpattern()
    {
        // given
        $pattern = Pattern::inject("(?-x:)foo#@\n,bar", [], 'x');
        // then
        $this->assertConsumesFirst('foo,bar', $pattern);
    }

    /**
     * @test
     */
    public function shouldEndComment()
    {
        // given
        $pattern = Pattern::inject("#@\nbar", [], 'x');
        // then
        $this->assertConsumesFirst('bar', $pattern);
    }
}

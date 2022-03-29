<?php
namespace Test\Feature\TRegx\CleanRegex\_extended\_comments;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsPattern;
use Test\Utils\TestCasePasses;
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
        $this->assertSamePattern("%You/her #@\n her?%ix", $pattern);
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
        $this->assertSamePattern("%You/her #Bar\n her?%X", $pattern);
    }

    /**
     * @test
     */
    public function shouldAddComment_SetExtended()
    {
        // when
        $pattern = Pattern::inject("(?x)You/her #@\n her?", []);
        // then
        $this->assertSamePattern("%(?x)You/her #@\n her?%", $pattern);
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
        $this->assertSamePattern("%(?X)You/her #Bar\n her?%", $pattern);
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
        $this->assertSamePattern($delimited, $pattern);
    }

    public function unsetExtendedFlag(): array
    {
        return [
            'unset flag'        => ["(?-x)#@\n", "/(?-x)#Foo\n/x"],
            'unset flag, short' => ["(?-x:#@\n)", "/(?-x:#Foo\n)/x"]
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
}

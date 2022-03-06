<?php
namespace Test\Feature\CleanRegex\_extended\_reset;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsPattern;
use TRegx\CleanRegex\Pattern;
use TRegx\Pcre;

class PatternTest extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     */
    public function shouldAddComment_ResetButSetAgain()
    {
        // when
        $pattern = Pattern::inject("(?^x)#@\n", []);

        // then
        $this->assertSamePattern("/(?^x)#@\n/", $pattern);
    }

    /**
     * @test
     */
    public function shouldUsePlaceholder_PatternExtended_Reset()
    {
        // when
        $pattern = Pattern::inject("(?^)#@\n", ['Foo'], 'x');

        // then
        $this->assertConsumesFirstPcre2("#Foo\n", $pattern);
        $this->assertSamePattern("/(?^)#Foo\n/x", $pattern);
    }

    /**
     * @test
     */
    public function shouldUsePlaceholder_PatternExtended_ResetShort()
    {
        // when
        $pattern = Pattern::inject("(?^:#@\n)", ['Foo'], 'x');

        // then
        $this->assertConsumesFirstPcre2("#Foo\n", $pattern);
        $this->assertSamePattern("/(?^:#Foo\n)/x", $pattern);
    }

    /**
     * @test
     */
    public function shouldUsePlaceholder_SubpatternExtended_Reset()
    {
        // when
        $pattern = Pattern::inject("(?x)(?^:#@\n)", ['Foo']);

        // then
        $this->assertConsumesFirstPcre2("#Foo\n", $pattern);
        $this->assertSamePattern("/(?x)(?^:#Foo\n)/", $pattern);
    }

    /**
     * @test
     * @depends shouldUsePlaceholder_PatternExtended_Reset
     * @depends shouldUsePlaceholder_SubpatternExtended_Reset
     */
    public function shouldAddComment_ResetButSetAgainCaseSensitiveFlag()
    {
        // when
        $pattern = Pattern::inject("(?^X)#@\n", ['Bar']);

        // then
        $this->assertSamePattern("/(?^X)#Bar\n/", $pattern);
    }

    /**
     * @test
     * @depends shouldUsePlaceholder_PatternExtended_Reset
     * @depends shouldUsePlaceholder_SubpatternExtended_Reset
     */
    public function shouldAddComment_ResetButSetAgainExtraExtended()
    {
        // when
        $pattern = Pattern::inject("(?^xx)#@\n", []);

        // then
        $this->assertSamePattern("/(?^xx)#@\n/", $pattern);
    }

    /**
     * @test
     * @depends shouldUsePlaceholder_PatternExtended_Reset
     * @depends shouldUsePlaceholder_SubpatternExtended_Reset
     */
    public function shouldAddComment_ResetButSetAgainCombination()
    {
        // when
        $pattern = Pattern::inject("(?^imx)#@\n", []);

        // then
        $this->assertSamePattern("/(?^imx)#@\n/", $pattern);
    }

    /**
     * @test
     * @depends shouldUsePlaceholder_PatternExtended_Reset
     * @depends shouldUsePlaceholder_SubpatternExtended_Reset
     */
    public function shouldUsePlaceholder_SetExtendedResetOtherSubpattern()
    {
        // when
        $pattern = Pattern::inject("(?x:(?^))#@\n", ['Bar']);

        // then
        $this->assertConsumesFirstPcre2("#Bar\n", $pattern);
        $this->assertSamePattern("/(?x:(?^))#Bar\n/", $pattern);
    }

    /**
     * @test
     * @depends shouldUsePlaceholder_PatternExtended_Reset
     * @depends shouldUsePlaceholder_SubpatternExtended_Reset
     */
    public function shouldUsePlaceholder_SetExtendedResetSubpattern()
    {
        // when
        $pattern = Pattern::inject("(?ix:(?^)#@\n)", ['Bar']);

        // then
        $this->assertConsumesFirstPcre2("#Bar\n", $pattern);
        $this->assertSamePattern("/(?ix:(?^)#Bar\n)/", $pattern);
    }

    /**
     * @test
     * @depends shouldUsePlaceholder_PatternExtended_Reset
     * @depends shouldUsePlaceholder_SubpatternExtended_Reset
     */
    public function shouldAddComment_SetExtendedReset()
    {
        // when
        $pattern = Pattern::inject("(?x)((?^))#@\n", []);

        // then
        $this->assertSamePattern("/(?x)((?^))#@\n/", $pattern);
    }

    /**
     * @test
     * @depends shouldUsePlaceholder_PatternExtended_Reset
     * @depends shouldUsePlaceholder_SubpatternExtended_Reset
     */
    public function shouldAddComment_SetExtendedResetShort()
    {
        // when
        $pattern = Pattern::inject("(?x)(?^:)#@\n", []);

        // then
        $this->assertSamePattern("/(?x)(?^:)#@\n/", $pattern);
    }

    private function assertConsumesFirstPcre2(string $string, Pattern $pattern): void
    {
        if (Pcre::pcre2()) {
            $this->assertConsumesFirst($string, $pattern);
        }
    }
}

<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\with;

use PHPUnit\Framework\TestCase;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldEscape_dollar_reference()
    {
        // when
        $result = pattern('(er|ab|ay|ey)')
            ->replace('P. Sherman, 42 Wallaby way, Sydney')
            ->first()
            ->with('$1');

        // then
        $this->assertEquals('P. Sh$1man, 42 Wallaby way, Sydney', $result);
    }

    /**
     * @test
     */
    public function shouldEscape_dollar_reference_whole()
    {
        // when
        $result = pattern('(er|ab|ay|ey)')
            ->replace('P. Sherman, 42 Wallaby way, Sydney')
            ->first()
            ->with('<$0>');

        // then
        $this->assertEquals('P. Sh<$0>man, 42 Wallaby way, Sydney', $result);
    }

    /**
     * @test
     */
    public function shouldEscape_dollar_reference_curly()
    {
        // when
        $result = pattern('(er|ab|ay|ey)')
            ->replace('P. Sherman, 42 Wallaby way, Sydney')
            ->first()
            ->with('<${0}>');

        // then
        $this->assertEquals('P. Sh<${0}>man, 42 Wallaby way, Sydney', $result);
    }

    /**
     * @test
     */
    public function shouldEscape_dollar_reference_curly_two_digits()
    {
        // when
        $result = pattern('(er|ab|ay|ey)')
            ->replace('P. Sherman, 42 Wallaby way, Sydney')
            ->first()
            ->with('<${11}>');

        // then
        $this->assertEquals('P. Sh<${11}>man, 42 Wallaby way, Sydney', $result);
    }

    /**
     * @test
     */
    public function shouldEscape_backslash_reference()
    {
        // when
        $result = pattern('(er|ab|ay|ey)')
            ->replace('P. Sherman, 42 Wallaby way, Sydney')
            ->first()
            ->with('<\\0>');

        // then
        $this->assertEquals('P. Sh<\\0>man, 42 Wallaby way, Sydney', $result);
    }

    /**
     * @test
     */
    public function shouldEscape_backslash_reference_two_digits()
    {
        // when
        $result = pattern('(er|ab|ay|ey)')
            ->replace('P. Sherman, 42 Wallaby way, Sydney')
            ->first()
            ->with('<\\11>');

        // then
        $this->assertEquals('P. Sh<\\11>man, 42 Wallaby way, Sydney', $result);
    }

    /**
     * @test
     */
    public function shouldEscape_backslash()
    {
        // when
        $result = pattern('(er|ab|ay|ey)')
            ->replace('P. Sherman, 42 Wallaby way, Sydney')
            ->first()
            ->with('\\\\');

        // then
        $this->assertEquals('P. Sh\\\\man, 42 Wallaby way, Sydney', $result);
    }
}

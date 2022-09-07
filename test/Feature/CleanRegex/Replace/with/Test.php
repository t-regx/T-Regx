<?php
namespace Test\Feature\CleanRegex\Replace\with;

use PHPUnit\Framework\TestCase;

class Test extends TestCase
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
        $this->assertSame('P. Sh$1man, 42 Wallaby way, Sydney', $result);
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
        $this->assertSame('P. Sh<$0>man, 42 Wallaby way, Sydney', $result);
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
        $this->assertSame('P. Sh<${0}>man, 42 Wallaby way, Sydney', $result);
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
        $this->assertSame('P. Sh<${11}>man, 42 Wallaby way, Sydney', $result);
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
        $this->assertSame('P. Sh<\\0>man, 42 Wallaby way, Sydney', $result);
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
        $this->assertSame('P. Sh<\\11>man, 42 Wallaby way, Sydney', $result);
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
        $this->assertSame('P. Sh\\\\man, 42 Wallaby way, Sydney', $result);
    }
}

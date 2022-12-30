<?php
namespace Test\Feature\CleanRegex\_prepared\template\mask;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\MaskMalformedPatternException;
use TRegx\CleanRegex\Pattern;

class Test extends TestCase
{
    /**
     * @test
     * @dataProvider \Test\DataProviders::invalidStandardPatterns()
     * @param string $invalidPattern
     */
    public function shouldNotValidateStandardPattern(string $invalidPattern)
    {
        // given
        $template = Pattern::template('@');
        // then
        $this->expectException(MaskMalformedPatternException::class);
        $this->expectExceptionMessage("Malformed pattern '$invalidPattern' assigned to keyword '%f'");
        // when
        $template->mask('%s', ['%f' => $invalidPattern]);
    }

    /**
     * @test
     */
    public function shouldStandardNotLeaveErrors()
    {
        // given
        \error_clear_last();
        $template = Pattern::template('@');
        // when
        try {
            $template->mask('%s', ['%s' => '/{2,1}/']);
        } catch (MaskMalformedPatternException $silenced) {
        }
        // then
        $this->assertErrorsEmpty();
    }

    private function assertErrorsEmpty(): void
    {
        $this->assertNull(\error_get_last());
        $this->assertSame(PREG_NO_ERROR, \preg_last_error());
    }

    /**
     * @test
     */
    public function shouldValidateMaskWithFlags()
    {
        // given
        $template = Pattern::template('^@$', 'x');
        // then
        $this->expectException(MaskMalformedPatternException::class);
        $this->expectExceptionMessage("Malformed pattern '#commen(t\nfoo)' assigned to keyword '*'");
        // when, then
        $template->mask('*', ['*' => "#commen(t\nfoo)"]);
    }
}

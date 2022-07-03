<?php
namespace Test\Feature\CleanRegex\_prepared\template\mask;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\MaskMalformedPatternException;
use TRegx\CleanRegex\Pattern;
use TRegx\SafeRegex\Internal\Errors\Errors\EmptyHostError;
use TRegx\SafeRegex\Internal\Errors\ErrorsCleaner;

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
        $errorsCleaner = new ErrorsCleaner();
        $this->assertInstanceOf(EmptyHostError::class, $errorsCleaner->getError());
    }
}

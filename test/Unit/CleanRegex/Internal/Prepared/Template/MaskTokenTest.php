<?php
namespace Test\Unit\CleanRegex\Internal\Prepared\Template;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\MaskMalformedPatternException;
use TRegx\CleanRegex\Internal\Prepared\Template\MaskToken;
use TRegx\SafeRegex\Internal\Errors\Errors\EmptyHostError;
use TRegx\SafeRegex\Internal\Errors\ErrorsCleaner;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\Template\MaskToken
 */
class MaskTokenTest extends TestCase
{
    /**
     * @test
     * @dataProvider \Test\DataProviders::invalidStandardPatterns()
     * @param string $invalidPattern
     */
    public function shouldNotValidateStandardPattern(string $invalidPattern)
    {
        // given
        $mask = new MaskToken('%s', ['%f' => $invalidPattern]);

        // then
        $this->expectException(MaskMalformedPatternException::class);
        $this->expectExceptionMessage("Malformed pattern '$invalidPattern' assigned to keyword '%f'");

        // when
        $mask->phrase();
    }

    /**
     * @test
     */
    public function shouldStandardNotLeaveErrors()
    {
        // given
        $mask = new MaskToken('%s', ['%s' => '/{2,1}/']);

        // when
        try {
            $mask->phrase();
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

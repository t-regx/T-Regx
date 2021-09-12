<?php
namespace Test\Interaction\TRegx\CleanRegex\Replace;

use Closure;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\ThrowSubject;
use Test\Utils\Definitions;
use Test\Utils\Functions;
use TRegx\CleanRegex\Replace\By\ByReplacePattern;
use TRegx\CleanRegex\Replace\LimitlessReplacePattern;
use TRegx\CleanRegex\Replace\SpecificReplacePattern;

/**
 * @covers \TRegx\CleanRegex\Replace\ReplacePatternImpl
 */
class ReplacePatternImplTest extends TestCase
{
    /**
     * @test
     * @dataProvider withMethods
     * @param string $methodName
     */
    public function shouldDelegate_with_and_withReferences(string $methodName)
    {
        // given
        /** @var SpecificReplacePattern|MockObject $delegate */
        $delegate = $this->createMock(SpecificReplacePattern::class);
        $delegate->method($methodName)->willReturn('delegated');
        $delegate->expects($this->exactly(1))->method($methodName)->with('number');

        $underTest = new LimitlessReplacePattern($delegate, Definitions::pcre('//'), new ThrowSubject());

        // when
        $result = $underTest->$methodName('number');

        // then
        $this->assertSame('delegated', $result);
    }

    function withMethods(): array
    {
        return [
            ['with'],
            ['withReferences']
        ];
    }

    /**
     * @test
     */
    public function shouldDelegate_callback()
    {
        // given
        /** @var SpecificReplacePattern|MockObject $delegate */
        $delegate = $this->createMock(SpecificReplacePattern::class);
        $delegate->method('callback')->willReturn('delegated');
        $delegate->expects($this->exactly(1))
            ->method('callback')
            ->with($this->callback(function (Closure $inputCallback) {
                return $inputCallback() === 'input';
            }));

        $underTest = new LimitlessReplacePattern($delegate, Definitions::pcre('//'), new ThrowSubject());

        // when
        $result = $underTest->callback(Functions::constant('input'));

        // then
        $this->assertSame('delegated', $result);
    }

    /**
     * @test
     */
    public function shouldDelegate_by()
    {
        // given
        $inputInstance = $this->createMock(ByReplacePattern::class);

        /** @var SpecificReplacePattern|MockObject $delegate */
        $delegate = $this->createMock(SpecificReplacePattern::class);
        $delegate->method('by')->willReturn($inputInstance);

        $underTest = new LimitlessReplacePattern($delegate, Definitions::pcre('//'), new ThrowSubject());

        // when
        $result = $underTest->by();

        // then
        $this->assertSame($inputInstance, $result);
    }
}

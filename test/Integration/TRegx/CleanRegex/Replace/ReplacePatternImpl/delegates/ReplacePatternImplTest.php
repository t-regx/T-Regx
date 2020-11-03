<?php
namespace Test\Integration\TRegx\CleanRegex\Replace\ReplacePatternImpl\delegates;

use Closure;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Replace\NonReplaced\ReplacePatternFactory;
use TRegx\CleanRegex\Replace\By\ByReplacePattern;
use TRegx\CleanRegex\Replace\ReplacePatternImpl;
use TRegx\CleanRegex\Replace\SpecificReplacePattern;

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

        $underTest = new ReplacePatternImpl($delegate, InternalPattern::pcre('//'), '', 0, new ReplacePatternFactory());

        // when
        $result = $underTest->$methodName('number');

        // then
        $this->assertEquals('delegated', $result);
    }

    function withMethods()
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

        $underTest = new ReplacePatternImpl($delegate, InternalPattern::pcre('//'), '', 0, new ReplacePatternFactory());

        // when
        $result = $underTest->callback(Functions::constant('input'));

        // then
        $this->assertEquals('delegated', $result);
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

        $underTest = new ReplacePatternImpl($delegate, InternalPattern::pcre('//'), '', 0, new ReplacePatternFactory());

        // when
        $result = $underTest->by();

        // then
        $this->assertEquals($inputInstance, $result);
    }
}

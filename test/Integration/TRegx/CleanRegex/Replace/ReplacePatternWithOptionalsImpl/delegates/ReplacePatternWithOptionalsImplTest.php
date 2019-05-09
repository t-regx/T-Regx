<?php
namespace Test\Integration\TRegx\CleanRegex\Replace\ReplacePatternWithOptionalsImplTest\delegates;

use Closure;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Replace\Map\MapReplacePattern;
use TRegx\CleanRegex\Replace\NonReplaced\ReplacePattern§;
use TRegx\CleanRegex\Replace\ReplacePattern;
use TRegx\CleanRegex\Replace\ReplacePatternWithOptionalsImpl;

class ReplacePatternWithOptionalsImplTest extends TestCase
{
    /**
     * @test
     * @dataProvider withMethods
     * @param string $methodName
     */
    public function shouldDelegate_with_and_withReferences(string $methodName)
    {
        // given
        /** @var ReplacePattern|MockObject $delegate */
        $delegate = $this->createMock(ReplacePattern::class);
        $delegate->method($methodName)->willReturn('delegated');
        $delegate->expects($this->exactly(1))->method($methodName)->with('number');

        $underTest = new ReplacePatternWithOptionalsImpl($delegate, new InternalPattern(''), '', 0, new ReplacePattern§());

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
        /** @var ReplacePattern|MockObject $delegate */
        $delegate = $this->createMock(ReplacePattern::class);
        $delegate->method('callback')->willReturn('delegated');
        $delegate->expects($this->exactly(1))
            ->method('callback')
            ->with($this->callback(function (Closure $inputCallback) {
                return $inputCallback() === 'input';
            }));

        $underTest = new ReplacePatternWithOptionalsImpl($delegate, new InternalPattern(''), '', 0, new ReplacePattern§());

        // when
        $result = $underTest->callback(function () {
            return 'input';
        });

        // then
        $this->assertEquals('delegated', $result);
    }

    /**
     * @test
     */
    public function shouldDelegate_by()
    {
        // given
        $inputInstance = $this->createMock(MapReplacePattern::class);

        /** @var ReplacePattern|MockObject $delegate */
        $delegate = $this->createMock(ReplacePattern::class);
        $delegate->method('by')->willReturn($inputInstance);

        $underTest = new ReplacePatternWithOptionalsImpl($delegate, new InternalPattern(''), '', 0, new ReplacePattern§());

        // when
        $result = $underTest->by();

        // then
        $this->assertEquals($inputInstance, $result);
    }
}

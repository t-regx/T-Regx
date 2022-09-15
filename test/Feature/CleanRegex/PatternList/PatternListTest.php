<?php
namespace Test\Feature\CleanRegex\PatternList;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\TestCase\TestCaseExactMessage;
use TRegx\CleanRegex\Pattern;
use TRegx\CleanRegex\PatternList;
use TRegx\CleanRegex\PcrePattern;

/**
 * @coversNothing
 */
class PatternListTest extends TestCase
{
    use TestCaseExactMessage;

    /**
     * @test
     */
    public function shouldAcceptPatternTypes()
    {
        // when, then
        $this->assertTrue($this->patterList()->failAll('Bar'));
        $this->assertTrue($this->patterList()->failAny('Bar'));
        $this->assertFalse($this->patterList()->testAny('Bar'));
        $this->assertFalse($this->patterList()->testAll('Bar'));
    }

    private function patterList(): PatternList
    {
        return Pattern::list([
            'Foo',
            Pattern::of('Foo'),
            Pattern::literal('Foo'),
            PcrePattern::of('/Foo/'),
            PcrePattern::inject('/@/', ['Foo'])
        ]);
    }

    /**
     * @test
     */
    public function shouldTestControlCharacter()
    {
        // given
        $list = Pattern::list(['\c\\']);
        // when, then
        $this->assertTrue($list->testAny(\chr(28)));
        $this->assertFalse($list->failAny(\chr(28)));
    }

    /**
     * @test
     */
    public function shouldFailControlCharacter()
    {
        // given
        $list = Pattern::list(['\c\\']);
        // when, then
        $this->assertTrue($list->failAny(\chr(27)));
        $this->assertFalse($list->testAny(\chr(27)));
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidType()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('PatternList can only compose type Pattern or string, but boolean (true) given');
        // when
        Pattern::list([true]);
    }
}

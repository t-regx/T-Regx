<?php
namespace Test\Feature\CleanRegex\PatternList;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;
use TRegx\CleanRegex\PatternList;
use TRegx\CleanRegex\PcrePattern;
use TRegx\Exception\MalformedPatternException;

/**
 * @coversNothing
 */
class PatternListTest extends TestCase
{
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
    public function shouldThrowForMalformedPattern()
    {
        // when
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Pattern may not end with a trailing backslash');
        // when
        Pattern::list(['\\']);
    }
}

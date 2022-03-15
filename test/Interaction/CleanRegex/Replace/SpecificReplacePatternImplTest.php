<?php
namespace Test\Interaction\TRegx\CleanRegex\Replace;

use PHPUnit\Framework\TestCase;
use Test\Utils\Definitions;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Internal\Replace\Counting\IgnoreCounting;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Replace\SpecificReplacePattern;
use TRegx\CleanRegex\Replace\SpecificReplacePatternImpl;

/**
 * @covers \TRegx\CleanRegex\Replace\SpecificReplacePatternImpl
 */
class SpecificReplacePatternImplTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReplaceWithString()
    {
        // given
        $replace = $this->createReplacePattern('192.168.173.180');

        // when
        $result = $replace->with('*');

        // then
        $this->assertSame('*.*.*.180', $result);
    }

    /**
     * @test
     */
    public function shouldReplaceWithCallback()
    {
        // given
        $replace = $this->createReplacePattern('192.168.173.180');

        // when
        $result = $replace->callback(function (Detail $detail) {
            if ($detail->toInt() < 175) {
                return '___';
            }
            return '^^^';
        });

        // then
        $this->assertSame('^^^.___.___.180', $result);
    }

    private function createReplacePattern(string $subject): SpecificReplacePattern
    {
        return new SpecificReplacePatternImpl(Definitions::pattern('[0-9]+'), new Subject($subject), 3, new DefaultStrategy(), new IgnoreCounting());
    }
}

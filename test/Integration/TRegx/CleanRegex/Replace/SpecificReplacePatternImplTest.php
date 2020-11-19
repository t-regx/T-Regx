<?php
namespace Test\Integration\TRegx\CleanRegex\Replace;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Replace\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Replace\SpecificReplacePattern;
use TRegx\CleanRegex\Replace\SpecificReplacePatternImpl;

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
        $this->assertEquals('*.*.*.180', $result);
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
        $this->assertEquals('^^^.___.___.180', $result);
    }

    private function createReplacePattern(string $subject): SpecificReplacePattern
    {
        return new SpecificReplacePatternImpl(InternalPattern::standard('[0-9]+'), $subject, 3, new DefaultStrategy());
    }
}

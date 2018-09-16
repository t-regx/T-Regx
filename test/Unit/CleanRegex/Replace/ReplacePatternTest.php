<?php
namespace Test\Unit\CleanRegex\Replace;

use CleanRegex\Internal\Pattern;
use CleanRegex\Match\ReplaceMatch;
use CleanRegex\Replace\ReplacePattern;
use PHPUnit\Framework\TestCase;

class ReplacePatternTest extends TestCase
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
        $result = $replace->callback(function (ReplaceMatch $match) {
            if ((string)$match < 175) {
                return '___';
            }
            return '^^^';
        });

        // then
        $this->assertEquals('^^^.___.___.180', $result);
    }

    private function createReplacePattern(string $subject): ReplacePattern
    {
        return new ReplacePattern(new Pattern('[0-9]+'), $subject, 3);
    }
}

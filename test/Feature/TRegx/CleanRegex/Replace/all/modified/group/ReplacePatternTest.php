<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\all\modified\group;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\ReplaceMatch;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetFromReplaceMatch_modifiedOffset()
    {
        // given
        $pattern = 'http://(?<name>[a-z]+)\.(?<domain>com|org)';
        $subject = 'Links: http://google.com and http://other.org. and again http://danon.com';

        $offsets = [];
        $mOffsets = [];

        $callback = function (ReplaceMatch $match) use (&$offsets, &$mOffsets) {
            $offsets[] = $match->group('name')->offset();
            $mOffsets[] = $match->group('name')->modifiedOffset();
            return 'ę';
        };

        // when
        pattern($pattern)->replace($subject)->all()->callback($callback);

        // then
        $this->assertEquals([14, 36, 64], $offsets);
        $this->assertEquals([14, 20, 33], $mOffsets);
    }
}

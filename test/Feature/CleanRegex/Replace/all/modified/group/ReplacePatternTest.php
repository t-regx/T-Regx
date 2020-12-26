<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\all\modified\group;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\ReplaceDetail;

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

        $callback = function (ReplaceDetail $detail) use (&$offsets, &$mOffsets) {
            $offsets[] = $detail->group('name')->offset();
            $mOffsets[] = $detail->group('name')->modifiedOffset();
            return 'ę';
        };

        // when
        pattern($pattern)->replace($subject)->all()->callback($callback);

        // then
        $this->assertSame([14, 36, 64], $offsets);
        $this->assertSame([14, 20, 33], $mOffsets);
    }
}

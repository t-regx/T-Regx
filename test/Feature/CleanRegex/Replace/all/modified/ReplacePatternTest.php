<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\all\modified;

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
            $offsets[] = $detail->offset();
            $mOffsets[] = $detail->modifiedOffset();
            return 'ę';
        };

        // when
        pattern($pattern)->replace($subject)->all()->callback($callback);

        // then
        $this->assertSame([7, 29, 57], $offsets);
        $this->assertSame([7, 13, 26], $mOffsets);
    }

    /**
     * @test
     */
    public function shouldGetFromReplaceMatch_modifiedSubject()
    {
        // given
        $pattern = '\*([a-zęó])\*';
        $subject = 'words: *ó* *ę* *ó*';

        $subjects = [];

        $callback = function (ReplaceDetail $detail) use (&$subjects) {
            $subjects[] = $detail->modifiedSubject();
            return 'a';
        };

        // when
        $result = pattern($pattern, 'u')->replace($subject)->all()->callback($callback);

        // then
        $expected = [
            'words: *ó* *ę* *ó*',
            'words: a *ę* *ó*',
            'words: a a *ó*',
        ];
        $this->assertSame($expected, $subjects);
        $this->assertSame('words: a a a', $result);
    }
}

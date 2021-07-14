<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\only\modified;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\ReplaceDetail;

/**
 * @coversNothing
 */
class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetFromReplaceMatch_modifiedOffset()
    {
        // given
        $pattern = 'http://(?<name>[a-z]+)\.(?<domain>com|org)';
        $subject = 'Linkś: http://google.com and http://other.org. and again http://danon.com';

        $offsets = [];

        $callback = function (ReplaceDetail $detail) use (&$offsets) {
            $offsets[] = $detail->modifiedOffset();
            return 'ę';
        };

        // when
        pattern($pattern)->replace($subject)->only(2)->callback($callback);

        // then
        $this->assertSame([7, 13], $offsets);
    }

    /**
     * @test
     */
    public function shouldGetFromReplaceMatch_modifiedSubject()
    {
        // given
        $pattern = '\*([a-zęó])\*';
        $subject = 'words: *ó* *ę* *ó*';

        $offsets = [];
        $mOffsets = [];
        $subjects = [];

        $callback = function (ReplaceDetail $detail) use (&$subjects, &$offsets, &$mOffsets) {
            $offsets[] = $detail->offset();
            $mOffsets[] = $detail->modifiedOffset();
            $subjects[] = $detail->modifiedSubject();
            return 'a';
        };

        // when
        $result = pattern($pattern, 'u')->replace($subject)->only(2)->callback($callback);

        // then
        $expected = [
            'words: *ó* *ę* *ó*',
            'words: a *ę* *ó*',
        ];
        $this->assertSame($expected, $subjects);
        $this->assertSame('words: a a *ó*', $result);
    }
}

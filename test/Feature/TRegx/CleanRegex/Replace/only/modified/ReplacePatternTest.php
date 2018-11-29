<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\only\modified;

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

        $callback = function (ReplaceMatch $match) use (&$offsets) {
            $offsets[] = $match->modifiedOffset();
            return 'ę';
        };

        // when
        pattern($pattern)->replace($subject)->only(2)->callback($callback);

        // then
        $this->assertEquals([7, 13], $offsets);
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

        $callback = function (ReplaceMatch $match) use (&$subjects, &$offsets, &$mOffsets) {
            $offsets[] = $match->offset();
            $mOffsets[] = $match->modifiedOffset();
            $subjects[] = $match->modifiedSubject();
            return 'a';
        };

        // when
        $result = pattern($pattern, 'u')->replace($subject)->only(2)->callback($callback);

        // then
        $expected = [
            'words: *ó* *ę* *ó*',
            'words: a *ę* *ó*',
        ];
        $this->assertEquals($expected, $subjects);
        $this->assertEquals('words: a a *ó*', $result);
    }
}

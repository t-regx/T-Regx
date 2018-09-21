<?php

namespace Test\Integration\TRegx\CleanRegex\Replace\all;

use TRegx\CleanRegex\Match\Details\ReplaceMatch;
use PHPUnit\Framework\TestCase;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReplace_withString()
    {
        // when
        $result = pattern('er|ab|ay|ey')->replace('P. Sherman, 42 Wallaby way, Sydney')->all()->with('*');

        // then
        $this->assertEquals('P. Sh*man, 42 Wall*y w*, Sydn*', $result);
    }

    /**
     * @test
     */
    public function shouldReplace_withCallback()
    {
        // given
        $pattern = 'http://(?<name>[a-z]+)\.(com|org)';
        $subject = 'Links: http://google.com, http://other.org and http://website.org.';

        // when
        $result = pattern($pattern)->replace($subject)->all()->callback(function (ReplaceMatch $match) {
            return $match->group('name');
        });

        // then
        $this->assertEquals($result, 'Links: google, other and website.');
    }

    /**
     * @test
     */
    public function shouldGetFromReplaceMatch_all()
    {
        // given
        $pattern = 'http://(?<name>[a-z]+)\.(?<domain>com|org)';
        $subject = 'Links: http://google.com and http://other.org. and again http://danon.com';

        // when
        pattern($pattern)->replace($subject)->all()->callback(function (ReplaceMatch $match) {
            // then
            $this->assertEquals(['http://google.com', 'http://other.org', 'http://danon.com'], $match->all());
            $this->assertEquals(['http://google.com', 'http://other.org', 'http://danon.com'], $match->allUnlimited());

            return '';
        });
    }

    /**
     * @test
     */
    public function shouldGetFromReplaceMatch_offset()
    {
        // given
        $pattern = 'http://(?<name>[a-zę]+)\.(?<domain>com|org)';
        $subject = 'Links: http://googlę.com and http://other.org. and again http://danon.com';

        $offsets = [];

        $callback = function (ReplaceMatch $match) use (&$offsets) {
            $offsets[] = $match->offset();
            return 'ę';
        };

        // when
        pattern($pattern)->replace($subject)->all()->callback($callback);

        // then
        $this->assertEquals([7, 29, 57], $offsets);
    }

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
        pattern($pattern)->replace($subject)->all()->callback($callback);

        // then
        $this->assertEquals([7, 13, 26], $offsets);
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
        $result = pattern($pattern, 'u')->replace($subject)->all()->callback($callback);

        // then
        $expected = [
            'words: *ó* *ę* *ó*',
            'words: a *ę* *ó*',
            'words: a a *ó*',
        ];
        $this->assertEquals($expected, $subjects);
        $this->assertEquals('words: a a a', $result);
    }
}

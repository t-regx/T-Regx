<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\all;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\ReplaceMatch;

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
    public function shouldGetGroup_offset()
    {
        // given
        $pattern = 'http://(?<name>[a-z]+)\.(?<domain>com|org)';
        $subject = 'Links: http://google.com and http://other.org. and again http://danon.com';

        // when
        pattern($pattern)->replace($subject)->all()->callback(function (ReplaceMatch $match) {
            $matchGroup = $match->group('name');
            if ($matchGroup->text() !== 'other') return '';

            // then
            $offset = $match->offset();
            $groupOffset = $match->group('name')->offset();

            // when
            $this->assertEquals(29, $offset);
            $this->assertEquals(36, $groupOffset);

            return '';
        });
    }

    /**
     * @test
     */
    public function shouldReturn_nonReplacedStrategy()
    {
        // when
        $result = pattern('Foo')->replace('Bar')->all()->orReturn('otherwise')->with('');

        // then
        $this->assertEquals('otherwise', $result);
    }
}

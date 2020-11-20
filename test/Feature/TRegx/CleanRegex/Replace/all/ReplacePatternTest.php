<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\all;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\ReplaceDetail;

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
        $result = pattern($pattern)->replace($subject)->all()->callback(function (ReplaceDetail $detail) {
            return $detail->group('name');
        });

        // then
        $this->assertEquals('Links: google, other and website.', $result);
    }

    /**
     * @test
     */
    public function shouldReplace_focus_with()
    {
        // given
        $pattern = 'http://(?<name>[a-z]+)\.(com|org)';
        $subject = 'Links: http://google.com, http://other.org and http://website.org.';

        // when
        $result = pattern($pattern)->replace($subject)->all()->focus('name')->with('xxx');

        // then
        $this->assertEquals('Links: http://xxx.com, http://xxx.org and http://xxx.org.', $result);
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
        pattern($pattern)->replace($subject)->all()->callback(function (ReplaceDetail $detail) {
            // then
            $this->assertEquals(['http://google.com', 'http://other.org', 'http://danon.com'], $detail->all());

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

        $callback = function (ReplaceDetail $detail) use (&$offsets) {
            $offsets[] = $detail->offset();
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
        pattern($pattern)->replace($subject)->all()->callback(function (ReplaceDetail $detail) {
            $matchGroup = $detail->group('name');
            if ($matchGroup->text() !== 'other') return '';

            // then
            $offset = $detail->offset();
            $groupOffset = $detail->group('name')->offset();

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
        $result = pattern('Foo')->replace('Bar')->all()->otherwiseReturning('otherwise')->with('');

        // then
        $this->assertEquals('otherwise', $result);
    }
}

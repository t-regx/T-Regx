<?php
namespace Test\Integration\CleanRegex\Replace;

use CleanRegex\Match\Details\ReplaceMatch;
use PHPUnit\Framework\TestCase;

class ReplacePatternAllTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReplaceString()
    {
        // when
        $result = pattern('er|ab|ay|ey')
            ->replace('P. Sherman, 42 Wallaby way, Sydney')
            ->all()
            ->with('*');

        // then
        $this->assertEquals('P. Sh*man, 42 Wall*y w*, Sydn*', $result);
    }

    /**
     * @test
     */
    public function shouldReplaceWithStringUsingCallback()
    {
        // given
        $pattern = 'http://(?<name>[a-z]+)\.(com|org)';
        $subject = 'Links: http://google.com, http://other.org and http://website.org.';

        // when
        $result = pattern($pattern)
            ->replace($subject)
            ->all()
            ->callback(function () {
                return 'a';
            });

        // then
        $this->assertEquals($result, 'Links: a, a and a.');
    }

    /**
     * @test
     */
    public function shouldReplaceWithCallback()
    {
        // given
        $pattern = 'http://(?<name>[a-z]+)\.(com|org)';
        $subject = 'Links: http://google.com, http://other.org and http://website.org.';

        // when
        $result = pattern($pattern)
            ->replace($subject)
            ->all()
            ->callback(function (ReplaceMatch $match) {
                return $match->group('name');
            });

        // then
        $this->assertEquals($result, 'Links: google, other and website.');
    }

    /**
     * @test
     */
    public function shouldGetAllFromReplaceMatch()
    {
        // given
        $pattern = 'http://(?<name>[a-z]+)\.(?<domain>com|org)';
        $subject = 'Links: http://google.com and http://other.org. and again http://danon.com';

        // when
        pattern($pattern)
            ->replace($subject)
            ->all()
            ->callback(function (ReplaceMatch $match) {
                // then
                $this->assertEquals(['http://google.com', 'http://other.org', 'http://danon.com'], $match->all());

                return '';
            });
    }

    /**
     * @test
     */
    public function shouldGetAllUnlimitedFromReplaceMatch()
    {
        // given
        $pattern = 'http://(?<name>[a-z]+)\.(?<domain>com|org)';
        $subject = 'Links: http://google.com and http://other.org. and again http://danon.com';

        // when
        pattern($pattern)
            ->replace($subject)
            ->all()
            ->callback(function (ReplaceMatch $match) {
                // then
                $this->assertEquals(['http://google.com', 'http://other.org', 'http://danon.com'], $match->allUnlimited());

                return '';
            });
    }

    /**
     * @test
     */
    public function shouldGetOffsetFromReplaceMatch()
    {
        // given
        $pattern = 'http://(?<name>[a-z]+)\.(?<domain>com|org)';
        $subject = 'Links: http://google.com and http://other.org. and again http://danon.com';

        $offsets = [];

        $callback = function (ReplaceMatch $match) use (&$offsets) {
            $offsets[] = $match->offset();
            return '';
        };

        // when
        pattern($pattern)->replace($subject)->all()->callback($callback);

        // then
        $this->assertEquals([7, 29, 57], $offsets);
    }

    /**
     * @test
     */
    public function shouldGetModifiedOffsetFromReplaceMatch()
    {
        // given
        $pattern = 'http://(?<name>[a-z]+)\.(?<domain>com|org)';
        $subject = 'Links: http://google.com and http://other.org. and again http://danon.com';

        $offsets = [];

        $callback = function (ReplaceMatch $match) use (&$offsets) {
            $offsets[] = $match->modifiedOffset();
            return 'a';
        };

        // when
        pattern($pattern)->replace($subject)->all()->callback($callback);

        // then
        $this->assertEquals([7, 13, 26], $offsets);
    }
}

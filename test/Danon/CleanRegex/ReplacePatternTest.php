<?php
namespace Danon\CleanRegex;

use Danon\CleanRegex\Match\ReplaceMatch;
use PHPUnit\Framework\TestCase;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReplaceString()
    {
        // when
        $result = pattern('er|ab|ay|ey')->replace('P. Sherman, 42 Wallaby way, Sydney')->with('*');

        // then
        $this->assertEquals('P. Sh*man, 42 Wall*y w*, Sydn*', $result);
    }

    /**
     * @test
     */
    public function shouldReplaceWithCallback()
    {
        // given
        $pattern = 'http://(?<name>[a-z]+)\.(com|org)';
        $subject = 'Links: http://google.com and http://other.org.';

        // when
        $result = pattern($pattern)
            ->replace($subject)
            ->callback(function (ReplaceMatch $match) {
                return $match->group('name');
            });

        // then
        $this->assertEquals($result, 'Links: google and other.');
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
            ->callback(function (ReplaceMatch $match) {

                // then
                $this->assertEquals(['http://google.com', 'http://other.org', 'http://danon.com'], $match->all());

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
        pattern($pattern)->replace($subject)->callback($callback);

        // then
        $this->assertEquals([7, 18, 29], $offsets);
    }

    /**
     * @test
     */
    public function shouldGetModifiedOffsetFromReplaceMatch()
    {
        // given
        $pattern = 'http://(?<name>[a-z]+)\.(?<domain>com|org)';
        $subject = 'Links: http://google.com and http://other.org. and again http://danon.com';
        $subject = 'Links: a and a. and again a';

        $offsets = [];

        $callback = function (ReplaceMatch $match) use (&$offsets) {
            $offsets[] = $match->modifiedOffset();
            return 'a';
        };

        // when
        pattern($pattern)->replace($subject)->callback($callback);

        // then
        $this->assertEquals([7, 8, 9], $offsets);
    }
}

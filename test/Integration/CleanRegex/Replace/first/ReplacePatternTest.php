<?php
namespace Test\Integration\CleanRegex\Replace\first;

use CleanRegex\Match\Details\ReplaceMatch;
use PHPUnit\Framework\TestCase;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReplace_withString()
    {
        // when
        $result = pattern('er|ab|ay|ey')
            ->replace('P. Sherman, 42 Wallaby way, Sydney')
            ->first()
            ->with('*');

        // then
        $this->assertEquals('P. Sh*man, 42 Wallaby way, Sydney', $result);
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
        $result = pattern($pattern)
            ->replace($subject)
            ->first()
            ->callback(function (ReplaceMatch $match) {
                return $match->group('name');
            });

        // then
        $this->assertEquals($result, 'Links: google, http://other.org and http://website.org.');
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
        pattern($pattern)
            ->replace($subject)
            ->first()
            ->callback(function (ReplaceMatch $match) {
                // then
                $this->assertEquals(['http://google.com'], $match->all());
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
        $pattern = 'http://(?<name>[a-z]+)\.(?<domain>com|org)';
        $subject = 'Links: http://google.com and http://other.org. and again http://danon.com';

        $offsets = [];

        $callback = function (ReplaceMatch $match) use (&$offsets) {
            $offsets[] = $match->offset();
            return '';
        };

        // when
        pattern($pattern)->replace($subject)->first()->callback($callback);

        // then
        $this->assertEquals([7], $offsets);
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
            return 'ą';
        };

        // when
        pattern($pattern)->replace($subject)->first()->callback($callback);

        // then
        $this->assertEquals([7], $offsets);
    }

    /**
     * @test
     */
    public function shouldGetFromReplaceMatch_modifiedSubject()
    {
        // given
        $pattern = 'http://(?<name>[a-z]+)\.(?<domain>com|org)';
        $subject = 'Links: http://google.com and http://other.org. and again http://google.com';

        $subjects = [];

        $callback = function (ReplaceMatch $match) use (&$subjects) {
            $subjects[] = $match->modifiedSubject();
            return '+' . $match->group('domain') . '+';
        };

        // when
        pattern($pattern)->replace($subject)->first()->callback($callback);

        // then
        $this->assertEquals(['Links: http://google.com and http://other.org. and again http://google.com'], $subjects);
    }
}

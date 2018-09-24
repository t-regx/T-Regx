<?php
namespace Test\Integration\TRegx\CleanRegex\Replace\first;

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
    public function shouldReplace_withGroup()
    {
        // given
        $pattern = 'http://(?<name>[a-z]+)\.(?<domain>com|org)';
        $subject = 'Links: http://google.com and http://other.org. and again http://danon.com';

        // when
        $result = pattern($pattern)
            ->replace($subject)
            ->first()
            ->callback(function (ReplaceMatch $match) {
                // then
                return $match->group('name');
            });

        // then
        $this->assertEquals('Links: google and http://other.org. and again http://danon.com', $result);
    }
}

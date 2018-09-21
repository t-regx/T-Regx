<?php
namespace Test\Integration\CleanRegex\Replace\only;

use CleanRegex\Match\Details\ReplaceMatch;
use InvalidArgumentException;
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
            ->only(2)
            ->with('*');

        // then
        $this->assertEquals('P. Sh*man, 42 Wall*y way, Sydney', $result);
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
            ->only(2)
            ->callback(function (ReplaceMatch $match) {
                return $match->group('name');
            });

        // then
        $this->assertEquals($result, 'Links: google, other and http://website.org.');
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
            ->only(2)
            ->callback(function (ReplaceMatch $match) {
                // then
                $this->assertEquals(['http://google.com', 'http://other.org'], $match->all());
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
        pattern($pattern)->replace($subject)->only(2)->callback($callback);

        // then
        $this->assertEquals([7, 29], $offsets);
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
            return 'a';
        };

        // when
        pattern($pattern)->replace($subject)->only(2)->callback($callback);

        // then
        $this->assertEquals([7, 13], $offsets);
    }

    /**
     * @test
     */
    public function shouldThrowOnNegativeLimit()
    {
        // given
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Negative limit -1");

        // when
        pattern('')->replace('')->only(-1);
    }

    /**
     * @test
     * @dataProvider limitAndExpectedResults
     * @param int $limit
     * @param string $expectedResult
     */
    public function shouldReplaceNOccurrences(int $limit, string $expectedResult)
    {
        // when
        $result = pattern('[0-3]')->replace('0 1 2 3')->only($limit)->with('*');

        // then
        $this->assertEquals($expectedResult, $result);
    }

    function limitAndExpectedResults()
    {
        return [
            [0, '0 1 2 3'],
            [1, '* 1 2 3'],
            [2, '* * 2 3'],
            [3, '* * * 3'],
        ];
    }
}

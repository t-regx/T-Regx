<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\only;

use InvalidArgumentException;
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
                $this->assertEquals(['http://google.com', 'http://other.org', 'http://danon.com'], $match->all());

                return '';
            });
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
            return '+' . $match->group('domain')->text() . '+';
        };

        // when
        pattern($pattern)->replace($subject)->only(2)->callback($callback);

        // then
        $expected = [
            'Links: http://google.com and http://other.org. and again http://google.com',
            'Links: +com+ and http://other.org. and again http://google.com'
        ];
        $this->assertEquals($expected, $subjects);
    }

    /**
     * @test
     */
    public function shouldReturn_substitute()
    {
        // when
        $result = pattern('Foo')->replace('Bar')->only(2)->orReturn('otherwise')->with('');

        // then
        $this->assertEquals('otherwise', $result);
    }
}

<?php
namespace Test\Feature\CleanRegex\Replace\only;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;
use TRegx\CleanRegex\Replace\Details\ReplaceDetail;

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
        $this->assertSame('P. Sh*man, 42 Wall*y way, Sydney', $result);
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
            ->callback(function (ReplaceDetail $detail) {
                // then
                $this->assertSame(['http://google.com', 'http://other.org', 'http://danon.com'], $detail->all());

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
        $this->expectExceptionMessage("Negative limit: -1");

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
        $this->assertSame($expectedResult, $result);
    }

    function limitAndExpectedResults(): array
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
    public function shouldThrow_only_onNegativeLimit()
    {
        // given
        $replace = Pattern::of('Foo')->replace('Bar');
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative limit: -2');
        // when
        $replace->only(-2);
    }
}

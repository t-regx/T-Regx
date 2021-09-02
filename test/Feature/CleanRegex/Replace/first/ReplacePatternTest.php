<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\first;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Replace\Details\ReplaceDetail;

/**
 * @coversNothing
 */
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
        $this->assertSame('P. Sh*man, 42 Wallaby way, Sydney', $result);
    }

    /**
     * @test
     */
    public function shouldReplace_withString_not_escaped()
    {
        // when
        $result = pattern('(er|ab|ay|ey)')
            ->replace('P. Sherman, 42 Wallaby way, Sydney')
            ->first()
            ->withReferences('*$1*');

        // then
        $this->assertSame('P. Sh*er*man, 42 Wallaby way, Sydney', $result);
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
            ->callback(function (ReplaceDetail $detail) {
                // then
                return $detail->group('name');
            });

        // then
        $this->assertSame('Links: google and http://other.org. and again http://danon.com', $result);
    }

    /**
     * @test
     */
    public function shouldReplace_withDetails()
    {
        // given
        $pattern = 'http://(?<name>[a-z]+)\.(?<domain>com|org)';
        $subject = 'Links: http://google.com and http://other.org. and again http://danon.com';

        // when
        $result = pattern($pattern)
            ->replace($subject)
            ->first()
            ->callback(function (ReplaceDetail $detail) {
                // then
                return $detail;
            });

        // then
        $this->assertSame($subject, $result);
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
            ->callback(function (ReplaceDetail $detail) {
                // then
                $this->assertSame(['http://google.com', 'http://other.org', 'http://danon.com'], $detail->all());

                return '';
            });
    }

    /**
     * @test
     */
    public function shouldReturn_nonReplacedStrategy()
    {
        // when
        $result = pattern('Foo')->replace('Bar')->first()->otherwiseReturning('otherwise')->with('XXX');

        // then
        $this->assertSame('otherwise', $result);
    }
}

<?php
namespace Test\Feature\CleanRegex\Replace\first;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Match\Detail;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldReplace_withString()
    {
        // when
        $replaced = pattern('er|ab|ay|ey')
            ->replace('P. Sherman, 42 Wallaby way, Sydney')
            ->first()
            ->with('*');
        // then
        $this->assertSame('P. Sh*man, 42 Wallaby way, Sydney', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplace_withGroup()
    {
        // when
        $replaced = pattern('"(12)"')
            ->replace('"12", "12", "12"')
            ->first()
            ->withGroup(1);
        // then
        $this->assertSame('12, "12", "12"', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplace_withString_not_escaped()
    {
        // when
        $replaced = pattern('(er|ab|ay|ey)')
            ->replace('P. Sherman, 42 Wallaby way, Sydney')
            ->first()
            ->withReferences('*$1*');
        // then
        $this->assertSame('P. Sh*er*man, 42 Wallaby way, Sydney', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplace_callback_withGroup()
    {
        // given
        $pattern = pattern('http://(?<name>[a-z]+)\.(?<domain>com|org)');
        $replace = $pattern->replace('Links: http://google.com and http://other.org. and again http://danon.com');

        // when
        $replaced = $replace->first()->callback(function (Detail $detail) {
            // then
            return $detail->group('name');
        });

        // then
        $this->assertSame('Links: google and http://other.org. and again http://danon.com', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplace_withDetails()
    {
        // given
        $pattern = pattern('http://(?<name>[a-z]+)\.(?<domain>com|org)');
        $subject = 'Links: http://google.com and http://other.org. and again http://danon.com';
        // when
        $replaced = $pattern
            ->replace($subject)
            ->first()
            ->callback(Functions::identity());
        // then
        $this->assertSame($subject, $replaced);
    }

    /**
     * @test
     */
    public function shouldGetFromReplaceMatch_all()
    {
        // given
        $pattern = pattern('http://(?<name>[a-z]+)\.(?<domain>com|org)');
        $replace = $pattern->replace('Links: http://google.com and http://other.org. and again http://danon.com');

        // when
        $replace->first()->callback(function (Detail $detail) {
            // then
            $this->assertSame(['http://google.com', 'http://other.org', 'http://danon.com'], $detail->all());

            return '';
        });
    }
}

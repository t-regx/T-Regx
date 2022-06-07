<?php
namespace Test\Feature\CleanRegex\Match\Detail\group\substitute;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

class DetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReplaceGroup()
    {
        // given
        $result = pattern('https?://(?<domain>[a-z.]+)/users')
            ->match('Link: http://facebook.com/users and https://google.com/users guys')
            ->map(function (Detail $detail) {
                // when
                return $detail->group('domain')->substitute('XD');
            });

        // then
        $this->assertSame(['http://XD/users', 'https://XD/users'], $result);
    }

    /**
     * @test
     */
    public function shouldReplaceEmpty()
    {
        // given
        $detail = pattern('https?://(?<domain>)/users')->match('Link: http:///users')->first();
        // when
        $result = $detail->group('domain')->substitute('Welp');
        // then
        $this->assertSame('http://Welp/users', $result);
    }

    /**
     * @test
     */
    public function shouldReplace_utf8()
    {
        // given
        $detail = pattern('hłłps?://(?<domain>ąść)/users')->match('Link: hłłp://ąść/users')->first();
        // when
        $result = $detail->group('domain')->substitute('ś');
        // then
        $this->assertSame('hłłp://ś/users', $result);
    }
}

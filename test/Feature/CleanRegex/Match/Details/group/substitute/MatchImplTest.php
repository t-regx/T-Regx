<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\group\substitute;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

class MatchImplTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReplaceGroup()
    {
        // given
        $result = pattern('https?://(?<domain>[\w.]+)/users')
            ->match('Link: http://facebook.com/users and https://google.com/users guys')
            ->map(function (Detail $detail) {
                // when
                return $detail->group('domain')->substitute('XD');
            });

        // then
        $this->assertEquals(['http://XD/users', 'https://XD/users'], $result);
    }

    /**
     * @test
     */
    public function shouldReplaceEmpty()
    {
        // given
        $result = pattern('https?://(?<domain>([\w.]+)?)/users')
            ->match('Link: http:///users')
            ->first(function (Detail $detail) {
                // when
                return $detail->group('domain')->substitute('Welp');
            });

        // then
        $this->assertEquals('http://Welp/users', $result);
    }

    /**
     * @test
     */
    public function shouldReplace_utf8()
    {
        // given
        $result = pattern('hłłps?://(?<domain>ąść)/users')
            ->match('Link: hłłp://ąść/users')
            ->first(function (Detail $detail) {
                // when
                return $detail->group('domain')->substitute('ś');
            });

        // then
        $this->assertEquals('hłłp://ś/users', $result);
    }
}

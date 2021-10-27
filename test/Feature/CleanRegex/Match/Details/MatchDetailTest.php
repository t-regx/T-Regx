<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

/**
 * @coversNothing
 */
class MatchDetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetTextLength()
    {
        // given
        $detail = $this->detail();

        // when
        $length = $detail->textLength();

        // then
        $this->assertSame(8, $length);
    }

    /**
     * @test
     */
    public function shouldGetTextByteLength()
    {
        // given
        $detail = $this->detail();

        // when
        $length = $detail->textByteLength();

        // then
        $this->assertSame(13, $length);
    }

    public function detail(): Detail
    {
        return pattern('Foo:.*')->match('Foo:€łść')->stream()->first();
    }
}

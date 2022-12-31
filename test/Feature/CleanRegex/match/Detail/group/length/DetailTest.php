<?php
namespace Test\Feature\CleanRegex\match\Detail\group\length;

use PHPUnit\Framework\TestCase;
use Test\Utils\Runtime\ExplicitStringEncoding;

class DetailTest extends TestCase
{
    use ExplicitStringEncoding;

    /**
     * @test
     */
    public function shouldGetGroupLength()
    {
        // given
        $detail = pattern('(\p{L}+)', 'u')->match('Łomża')->first();
        // then
        $this->assertSame(5, $detail->group(1)->length());
    }

    /**
     * @test
     */
    public function shouldGetGroupByteLength()
    {
        // given
        $detail = pattern('(\p{L}+)', 'u')->match('Łomża')->first();
        // then
        $this->assertSame(7, $detail->group(1)->byteLength());
    }
}

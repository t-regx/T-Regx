<?php
namespace Test\Feature\CleanRegex\match\Detail\group\length;

use PHPUnit\Framework\TestCase;
use Test\Utils\Runtime\ExplicitStringEncoding;
use TRegx\CleanRegex\Pattern;

class DetailTest extends TestCase
{
    use ExplicitStringEncoding;

    /**
     * @test
     */
    public function shouldGetGroupLength()
    {
        // given
        $detail = Pattern::of('(\p{L}+)', 'u')->match('Łomża')->first();
        // then
        $this->assertSame(5, $detail->group(1)->length());
    }

    /**
     * @test
     */
    public function shouldGetGroupByteLength()
    {
        // given
        $detail = Pattern::of('(\p{L}+)', 'u')->match('Łomża')->first();
        // then
        $this->assertSame(7, $detail->group(1)->byteLength());
    }
}

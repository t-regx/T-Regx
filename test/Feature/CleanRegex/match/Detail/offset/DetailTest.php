<?php
namespace Test\Feature\CleanRegex\match\Detail\offset;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\Runtime\ExplicitStringEncoding;
use TRegx\CleanRegex\Pattern;

class DetailTest extends TestCase
{
    use ExplicitStringEncoding;

    /**
     * @test
     */
    public function shouldGetOffset()
    {
        // when
        $detail = Pattern::of('Tomek')->match('€€€€, Tomek')->first();
        // when
        $offset = $detail->offset();
        $byteOffset = $detail->byteOffset();
        // then
        $this->assertSame(6, $offset);
        $this->assertSame(14, $byteOffset);
    }

    /**
     * @test
     */
    public function shouldGetOffset_forEach()
    {
        // given
        Pattern::of('(Tomek|Kamil)')->match('€€€€, Tomek i Kamil')->forEach(Functions::outLast($detail));
        // when
        $offset = $detail->offset();
        $byteOffset = $detail->byteOffset();
        // then
        $this->assertSame(14, $offset);
        $this->assertSame(22, $byteOffset);
    }
}

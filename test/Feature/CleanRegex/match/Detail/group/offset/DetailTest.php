<?php
namespace Test\Feature\CleanRegex\match\Detail\group\offset;

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
    public function shouldGetOffset_first()
    {
        // when
        $detail = Pattern::of('[€$](\d+)')->match('Cześć: €12')->first();
        // when
        $offset = $detail->group(1)->offset();
        $byteOffset = $detail->group(1)->byteOffset();
        // then
        $this->assertSame(8, $offset);
        $this->assertSame(12, $byteOffset);
    }

    /**
     * @test
     */
    public function shouldGetOffset_forEach()
    {
        // when
        Pattern::of('[€$](\d+)')->match('Cześć: €12, $132, €14')->forEach(Functions::collect($details));
        // when
        [$first, $second, $third] = $details;

        $this->assertSame(8, $first->group(1)->offset());
        $this->assertSame(12, $first->group(1)->byteOffset());

        $this->assertSame(13, $second->group(1)->offset());
        $this->assertSame(17, $second->group(1)->byteOffset());

        $this->assertSame(19, $third->group(1)->offset());
        $this->assertSame(25, $third->group(1)->byteOffset());
    }
}

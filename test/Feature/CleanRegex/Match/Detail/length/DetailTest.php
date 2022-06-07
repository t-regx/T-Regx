<?php
namespace Test\Feature\CleanRegex\Match\Detail\length;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use Test\Utils\Functions;
use Test\Utils\Runtime\ExplicitStringEncoding;
use Test\Utils\Structure\AssertsStructure;
use Test\Utils\Structure\Expect;

class DetailTest extends TestCase
{
    use AssertsDetail, ExplicitStringEncoding, AssertsStructure;

    /**
     * @test
     */
    public function shouldGetLength()
    {
        // when
        $detail = pattern('\S{4,}')->match('die Vögel')->first();
        // when
        $length = $detail->length();
        $byteLength = $detail->byteLength();
        // then
        $this->assertDetailText('Vögel', $detail);
        $this->assertSame(5, $length);
        $this->assertSame(6, $byteLength);
    }

    /**
     * @test
     */
    public function shouldGetTail_forEach()
    {
        // when
        pattern('[^ ,]+')->match('👸, 👹👹👹, 👺👺')->forEach(Functions::collect($details));
        // then
        $this->assertStructure($details, [
            Expect::length(1, 4),
            Expect::length(3, 12),
            Expect::length(2, 8),
        ]);
    }
}

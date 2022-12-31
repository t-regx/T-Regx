<?php
namespace Test\Feature\CleanRegex\match\Detail\tail;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use Test\Utils\Functions;
use Test\Utils\Runtime\ExplicitStringEncoding;

class DetailTest extends TestCase
{
    use AssertsDetail, ExplicitStringEncoding;

    /**
     * @test
     */
    public function shouldGetTail()
    {
        // when
        $detail = pattern('\S{4,}')->match('die Vögel')->first();
        // when
        $tail = $detail->tail();
        $byteTail = $detail->byteTail();
        // then
        $this->assertDetailText("Vögel", $detail);
        $this->assertSame(9, $tail);
        $this->assertSame(10, $byteTail);
    }

    /**
     * @test
     */
    public function shouldGetTail_forEach()
    {
        // when
        pattern('[^ ,]+')->match('👸, 👹, 👺')->forEach(Functions::collect($details));
        // then
        [$first, $second, $third] = $details;
        $this->assertSame(1, $first->tail());
        $this->assertSame(4, $first->byteTail());

        $this->assertSame(4, $second->tail());
        $this->assertSame(10, $second->byteTail());

        $this->assertSame(7, $third->tail());
        $this->assertSame(16, $third->byteTail());
    }
}

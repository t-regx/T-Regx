<?php
namespace Test\Feature\CleanRegex\Replace\callback;

use PHPUnit\Framework\TestCase;
use Test\Utils\DetailFunctions;
use Test\Utils\Functions;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Pattern;

/**
 * @coversNothing
 */
class ReplacePatternTest extends TestCase
{
    use TestCasePasses;

    /**
     * @test
     */
    public function shouldPassOffsets()
    {
        // given
        $subject = 'Tom Cruise is 21 years old and has 192cm';
        // when
        Pattern::of('[0-9]+')
            ->replace($subject)
            ->first()
            ->callback(DetailFunctions::out($detail, ''));
        // then
        $this->assertSame(14, $detail->offset());
        $this->assertSame($subject, $detail->subject());
        $this->assertSame(['21', '192'], $detail->all());
    }

    /**
     * @test
     */
    public function shouldInvokeUpToLimit()
    {
        // when
        Pattern::of('.')->replace('Lorem')->only(3)->callback(Functions::collect($details, ''));
        // then
        $this->assertSame(3, \count($details));
    }

    /**
     * @test
     */
    public function shouldNotInvokeCallback()
    {
        // given
        Pattern::of('Foo')->replace('Bar')->callback(Functions::fail());
        // then
        $this->pass();
    }
}

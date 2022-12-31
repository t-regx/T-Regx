<?php
namespace Test\Feature\CleanRegex\replace\callback\Detail\subject;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldGetSubject()
    {
        // given
        pattern('(?<matched>Foo)(?<unmatched>Bar)?')->replace('Hello:Foo')->callback(Functions::out($detail, ''));
        // when, then
        $this->assertSame('Hello:Foo', $detail->subject());
    }
}

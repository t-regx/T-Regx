<?php
namespace Test\Feature\CleanRegex\replace\callback\Detail\subject;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Pattern;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldGetSubject()
    {
        // given
        Pattern::of('(?<matched>Foo)(?<unmatched>Bar)?')
            ->replace('Hello:Foo')
            ->callback(Functions::out($detail, ''));
        // when, then
        $this->assertSame('Hello:Foo', $detail->subject());
    }
}

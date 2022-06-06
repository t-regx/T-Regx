<?php
namespace Test\Feature\CleanRegex\Replace\Details\subject;

use PHPUnit\Framework\TestCase;
use Test\Utils\DetailFunctions;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetSubject()
    {
        // given
        pattern('(?<matched>Foo)(?<unmatched>Bar)?')
            ->replace('Hello:Foo')
            ->first()
            ->callback(DetailFunctions::out($detail, ''));
        // when
        $this->assertSame('Hello:Foo', $detail->group('matched')->subject());
        $this->assertSame('Hello:Foo', $detail->group('unmatched')->subject());
    }
}

<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\Details\subject;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Replace\Details\ReplaceDetail;

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
            ->callback(function (ReplaceDetail $detail) {
                $this->assertSame('Hello:Foo', $detail->group('matched')->subject());
                $this->assertSame('Hello:Foo', $detail->group('unmatched')->subject());

                // cleanup
                return '';
            });
    }
}

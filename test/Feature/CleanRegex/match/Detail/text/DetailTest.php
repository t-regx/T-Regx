<?php
namespace Test\Feature\CleanRegex\match\Detail\text;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

class DetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetText()
    {
        // given
        $detail = Pattern::of("Here's Johnny!")->match("Here's Johnny!")->first();
        // when
        $text = $detail->text();
        // then
        $this->assertSame("Here's Johnny!", $text);
    }

    /**
     * @test
     */
    public function shouldCastToString()
    {
        // given
        $detail = Pattern::of('Bond')->match('James Bond')->first();
        // when
        $text = "$detail";
        // then
        $this->assertSame('Bond', $text);
    }
}

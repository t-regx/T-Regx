<?php
namespace Test\Interaction\TRegx\CleanRegex\Remove;

use PHPUnit\Framework\TestCase;
use Test\Utils\Internal;
use TRegx\CleanRegex\Remove\RemovePattern;

class RemovePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldRemoveAll()
    {
        // given
        $pattern = new RemovePattern(Internal::pattern('\d+'), 'My ip 172.168.13.2 address', -1);

        // when
        $result = $pattern->remove();

        // then
        $this->assertSame('My ip ... address', $result);
    }

    /**
     * @test
     */
    public function shouldRemoveLimit()
    {
        // given
        $pattern = new RemovePattern(Internal::pattern('\d+'), 'My ip 172.168.13.2 address', 2);

        // when
        $result = $pattern->remove();

        // then
        $this->assertSame('My ip ..13.2 address', $result);
    }
}

<?php
namespace Test\Feature\CleanRegex\Match\stream\keys;

use PHPUnit\Framework\TestCase;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern
 */
class MatchPatternTest extends TestCase
{
    use TestCasePasses;

    /**
     * @test
     */
    public function shouldGetFirstKey()
    {
        // given
        $stream = pattern('\d+')->match('123, 456, 789')->stream();
        // when
        $key = $stream->keys()->first();
        // then
        $this->assertSame(0, $key);
    }

    /**
     * @test
     */
    public function shouldChainingKeysValidatePattern()
    {
        // given
        $match = Pattern::of('+')->match('Foo');
        // then
        $this->expectException(MalformedPatternException::class);
        // when
        $match->stream()->keys()->keys()->keys()->first();
    }
}

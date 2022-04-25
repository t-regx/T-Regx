<?php
namespace Test\Feature\TRegx\CleanRegex\Match\stream\keys;

use PHPUnit\Framework\TestCase;
use Test\Utils\DetailFunctions;
use Test\Utils\Functions;
use Test\Utils\TestCasePasses;
use TRegx\CleanRegex\Exception\NoSuchStreamElementException;
use TRegx\CleanRegex\Match\Stream;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

/**
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\KeyStream
 */
class MatchPatternTest extends TestCase
{
    use TestCasePasses;

    /**
     * @test
     */
    public function shouldGetAllKeys()
    {
        // when
        $keys = $this->streamOf(['a' => 'One', 'b' => 'Two', 'c' => 'Three'])->keys()->all();
        // then
        $this->assertSame(['a', 'b', 'c'], $keys);
    }

    /**
     * @test
     */
    public function shouldGetKeysKeys()
    {
        // when
        $keysKeys = $this->streamOf(['a' => 'One', 'b' => 'Two', 'c' => 'Three'])
            ->keys()
            ->keys()
            ->all();
        // then
        $this->assertSame([0, 1, 2], $keysKeys);
    }

    /**
     * @test
     */
    public function shouldGetFirstKey()
    {
        // when
        $firstKey = $this->streamOf(['a' => 'One', 'b' => 'Two', 'c' => 'Three'])
            ->keys()
            ->first();
        // then
        $this->assertSame('a', $firstKey);
    }

    /**
     * @test
     */
    public function shouldGetFirstKeyKey()
    {
        // when
        $firstKey = $this->streamOf(['a' => 'One', 'b' => 'Two', 'c' => 'Three'])
            ->keys()
            ->keys()
            ->first();
        // then
        $this->assertSame(0, $firstKey);
    }

    /**
     * @test
     */
    public function shouldKeysFirstThrowForUnmatchedStream()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');
        // when
        pattern('Foo')->match('Bar')->stream()->keys()->first();
    }

    /**
     * @test
     */
    public function shouldKeysKeysFirstThrowForUnmatchedStream()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');
        // when
        pattern('Foo')->match('Bar')->stream()->keys()->keys()->first();
    }

    /**
     * @test
     */
    public function shouldFirstKeyCallFirstFlatMap()
    {
        // when
        Pattern::of('Foo')->match('Foo')
            ->stream()
            ->flatMap(Functions::pass(['key']))
            ->keys()
            ->first();
    }

    /**
     * @test
     */
    public function shouldFirstKeyKeyCallFirstFlatMap()
    {
        // when
        Pattern::of('Foo')->match('Foo')
            ->stream()
            ->flatMap(Functions::pass(['key']))
            ->keys()
            ->keys()
            ->first();
    }

    /**
     * @test
     */
    public function shouldFirstKeyCallFirstMap()
    {
        // when
        Pattern::of('\w+')->match('Foo,Bar')
            ->stream()
            ->map(Functions::assertSame('Foo', DetailFunctions::text()))
            ->keys()
            ->first();
    }

    /**
     * @test
     */
    public function shouldFirstKeyKeyCallFirstMap()
    {
        // when
        Pattern::of('\w+')->match('Foo,Bar')
            ->stream()
            ->map(Functions::assertSame('Foo', DetailFunctions::text()))
            ->keys()
            ->keys()
            ->first();
    }

    /**
     * @test
     */
    public function shouldKeysCallFlatMapOnce()
    {
        // when
        Pattern::of('Foo')
            ->match('Foo')
            ->stream()
            ->flatMap(Functions::once(['key']))
            ->keys()
            ->first();
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldKeysKeysCallFlatMapOnce()
    {
        // when
        Pattern::of('Foo')
            ->match('Foo')
            ->stream()
            ->flatMap(Functions::once(['key']))
            ->keys()
            ->keys()
            ->first();
        // then
        $this->pass();
    }

    private function streamOf(array $elements): Stream
    {
        return Pattern::of('Foo')->match('Foo')
            ->stream()
            ->flatMapAssoc(Functions::constant($elements));
    }

    /**
     * @test
     */
    public function shouldChainingKeysValidatePattern()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        // when
        Pattern::of('+')->match('Foo')->stream()->keys()->keys()->keys()->first();
    }
}

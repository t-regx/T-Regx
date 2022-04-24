<?php
namespace Test\Feature\TRegx\CleanRegex\Match\stream\values;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\TestCasePasses;
use TRegx\CleanRegex\Exception\NoSuchStreamElementException;
use TRegx\CleanRegex\Match\Stream;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\ValueStream
 */
class MatchPatternTest extends TestCase
{
    use TestCasePasses;

    /**
     * @test
     */
    public function shouldGetValues()
    {
        // when
        $values = $this->singletonStream()
            ->flatMapAssoc(Functions::constant([10 => 'One', 20 => 'Two', 30 => 'Three']))
            ->values()
            ->all();
        // then
        $this->assertSame(['One', 'Two', 'Three'], $values);
    }

    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // when
        $firstValue = $this->singletonStream()
            ->flatMapAssoc(Functions::constant([10 => 'One', 20 => 'Two', 30 => 'Three']))
            ->values()
            ->first();
        // then
        $this->assertSame('One', $firstValue);
    }

    /**
     * @test
     */
    public function shouldGetFirstKeyInteger()
    {
        // when
        $firstKey = $this->singletonStream()
            ->flatMapAssoc(Functions::constant([10 => 'One', 20 => 'Two', 30 => 'Three']))
            ->values()
            ->keys()
            ->first();
        // then
        $this->assertSame(0, $firstKey);
    }

    /**
     * @test
     */
    public function shouldGetFirstKeyString()
    {
        // when
        $firstKey = $this->singletonStream()
            ->flatMapAssoc(Functions::constant(['One' => 'One']))
            ->values()
            ->keys()
            ->first();
        // then
        $this->assertSame(0, $firstKey);
    }

    /**
     * @test
     */
    public function shouldFirstThrow()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        // when
        Pattern::of('Foo')->match('Foo')
            ->stream()
            ->flatMap(Functions::constant([]))
            ->values()
            ->first();
    }

    /**
     * @test
     */
    public function shouldFirstKeyThrow()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        // when
        Pattern::of('Foo')->match('Foo')
            ->stream()
            ->flatMap(Functions::constant([]))
            ->values()
            ->keys()
            ->first();
    }

    /**
     * @test
     */
    public function shouldCallPreviousFirstKey()
    {
        // when
        Pattern::of('Foo')->match('Foo')
            ->stream()
            ->flatMap(Functions::pass(['key']))
            ->values()
            ->keys()
            ->first();
    }

    private function singletonStream(): Stream
    {
        return Pattern::of('Foo')->match('Foo')->stream();
    }
}

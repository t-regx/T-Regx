<?php
namespace Test\Feature\CleanRegex\match\reduce;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\TestCase\TestCasePasses;
use Test\Utils\TypeFunctions;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\Search
 */
class SearchTest extends TestCase
{
    use TestCasePasses;

    /**
     * @test
     */
    public function shouldReduce_returnAccumulatorForEmptyMatch()
    {
        // when
        $result = Pattern::of('Beep')->search('Boop')->reduce(Functions::fail(), 12);
        // then
        $this->assertSame(12, $result);
    }

    /**
     * @test
     */
    public function shouldReduce_passAccumulatorAsFirstArgument()
    {
        // when
        $result = Pattern::of('Foo')->search('Foo')->reduce(Functions::identity(), 'Accumulator');
        // then
        $this->assertSame('Accumulator', $result);
    }

    /**
     * @test
     */
    public function shouldReduce_passFromCallbackForSingleMatch()
    {
        // when
        $result = Pattern::of('Match')->search('Match')->reduce(Functions::constant('Lorem'), 'Accumulator');
        // then
        $this->assertSame('Lorem', $result);
    }

    /**
     * @test
     */
    public function shouldReduce_passDetailSecondAsArgumentString()
    {
        // when
        $result = Pattern::of('Match')->search('Match')->reduce(Functions::secondArgument(), 'Accumulator');
        // then
        $this->assertSame('Match', $result);
    }

    /**
     * @test
     */
    public function shouldReduce_passDetailSecondAsArgumentTypeString()
    {
        // when
        Pattern::of('Foo')->search('Foo')->reduce(TypeFunctions::assertTypeStringString(), 'Accumulator');
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldReduce_throwForTooManyArguments()
    {
        // given
        $tooManyArguments = function ($one, $two, $three) {
            $this->fail();
        };
        // then
        $this->expectException(\ArgumentCountError::class);
        // when
        Pattern::of('Foo')->search('Foo')->reduce($tooManyArguments, 'Accumulator');
    }

    /**
     * @test
     */
    public function shouldReduce_throwForInvalidArgument()
    {
        // given
        $tooManyArguments = function ($one, int $invalid) {
            $this->fail();
        };
        // then
        $this->expectException(\TypeError::class);
        // when
        Pattern::of('Foo')->search('Foo')->reduce($tooManyArguments, 'Accumulator');
    }

    /**
     * @test
     */
    public function shouldReduce_throwForNonCallback()
    {
        // then
        $this->expectException(\TypeError::class);
        // when
        Pattern::of('Foo')->search('Foo')->reduce(null, 'Accumulator');
    }

    /**
     * @test
     */
    public function shouldReduceSecond()
    {
        // when
        $reduced = Pattern::of('\w+')->search('Foo, Bar')->reduce(Functions::secondArgument(), 'Accumulator');
        // then
        $this->assertSame('Bar', $reduced);
    }

    /**
     * @test
     */
    public function shouldPassAccumulator()
    {
        // when
        $reduced = Pattern::of('\d+')->search('15,16,17')->reduce(Functions::sum(), 0);
        // then
        $this->assertSame(48, $reduced);
    }

    /**
     * @test
     */
    public function shouldReturnNull()
    {
        // when
        $reduced = Pattern::of('Foo')->search('Foo,Foo')->reduce(Functions::constant(null), 0);
        // then
        $this->assertNull($reduced);
    }

    /**
     * @test
     */
    public function shouldReturnNullAccumulator()
    {
        // when
        $reduced = Pattern::of('Foo')->search('Bar')->reduce(Functions::fail(), null);
        // then
        $this->assertNull($reduced);
    }
}

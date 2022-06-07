<?php
namespace Test\Feature\CleanRegex\Match\stream\reduce;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Match\Details\Detail;
use function pattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReduce_returnAccumulatorForEmptyMatch()
    {
        // when
        $result = pattern('Foo')->match('Bar')
            ->stream()
            ->reduce(Functions::fail(), 12);

        // then
        $this->assertSame(12, $result);
    }

    /**
     * @test
     */
    public function shouldReduce_passFromCallbackForSingleMatch()
    {
        // when
        $result = pattern('Match')->match('Match')
            ->stream()
            ->reduce(Functions::constant('Lorem'), 'Accumulator');

        // then
        $this->assertSame('Lorem', $result);
    }

    /**
     * @test
     */
    public function shouldReduce_passAccumulatorAsFirstArgument()
    {
        // when
        $result = pattern('Foo')->match('Foo')
            ->stream()
            ->reduce(Functions::identity(), 'Accumulator');

        // then
        $this->assertSame('Accumulator', $result);
    }

    /**
     * @test
     */
    public function shouldReduce_passDetailSecondAsArgumentString()
    {
        // given
        $secondString = function ($acc, string $string) {
            return $string;
        };

        // when
        $result = pattern('Match')->match('Match')
            ->stream()
            ->reduce($secondString, 'Accumulator');

        // then
        $this->assertSame('Match', $result);
    }

    /**
     * @test
     */
    public function shouldReduce_passDetailSecondAsArgumentDetail()
    {
        // given
        $detailText = function ($acc, Detail $detail) {
            return $detail->text();
        };

        // when
        $result = pattern('Foo')->match('Foo')
            ->stream()
            ->reduce($detailText, 'Accumulator');

        // then
        $this->assertSame('Foo', $result);
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
        pattern('Foo')->match('Foo')
            ->stream()
            ->reduce($tooManyArguments, 'Accumulator');
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
        pattern('Foo')->match('Foo')
            ->stream()
            ->reduce($tooManyArguments, 'Accumulator');
    }

    /**
     * @test
     */
    public function shouldReduce_throwForNonCallback()
    {
        // then
        $this->expectException(\TypeError::class);

        // when
        pattern('Foo')->match('Foo')
            ->stream()
            ->reduce(null, 'Accumulator');
    }

    /**
     * @test
     */
    public function shouldReduceSecond()
    {
        // when
        $reduced = pattern('\w+')->match('Foo, Bar')
            ->stream()
            ->reduce(Functions::secondArgument(), 'Accumulator');

        // then
        $this->assertSame('Bar', $reduced->text());
    }

    /**
     * @test
     */
    public function shouldPassAccumulator()
    {
        // when
        $reduced = pattern('\d+')->match('15,16,17')
            ->stream()
            ->reduce(Functions::sum(), 0);

        // then
        $this->assertSame(48, $reduced);
    }

    /**
     * @test
     */
    public function shouldReturnNull()
    {
        // when
        $reduced = pattern('Foo')->match('Foo,Foo')
            ->stream()
            ->reduce(Functions::constant(null), 0);

        // then
        $this->assertNull($reduced);
    }

    /**
     * @test
     */
    public function shouldReturnNullAccumulator()
    {
        // when
        $reduced = pattern('Foo')->match('Bar')
            ->stream()
            ->reduce(Functions::fail(), null);

        // then
        $this->assertNull($reduced);
    }
}

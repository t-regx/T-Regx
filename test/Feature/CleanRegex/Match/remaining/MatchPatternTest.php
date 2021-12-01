<?php
namespace Test\Feature\TRegx\CleanRegex\Match\remaining;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsSameMatches;
use Test\Utils\DetailFunctions;
use Test\Utils\Functions;

class MatchPatternTest extends TestCase
{
    use AssertsSameMatches;

    /**
     * @test
     */
    public function shouldGet_test()
    {
        $this->markTestIncomplete();
        // when
        $matched = pattern('[A-Z][a-z]+')->match('First, Second, Third')->stream()->filter(DetailFunctions::equals('Third'))->test();

        // then
        $this->assertTrue($matched);
    }

    /**
     * @test
     */
    public function shouldGet_test_filteredOut()
    {
        $this->markTestIncomplete();
        // when
        $matched = pattern('[A-Z][a-z]+')->match('First, Second')->remaining(Functions::constant(false))->test();

        // then
        $this->assertFalse($matched);
    }

    /**
     * @test
     */
    public function shouldGet_test_notMatched()
    {
        $this->markTestIncomplete();
        // when
        $matched = pattern('Foo')->match('Bar')->remaining(Functions::fail())->test();

        // then
        $this->assertFalse($matched);
    }

    /**
     * @test
     */
    public function shouldReturn_all()
    {
        // when
        $filtered = pattern('[A-Z][a-z]+')->match('First, Second, Third, Fourth, Fifth')
            ->remaining(Functions::oneOf(['First', 'Third', 'Fifth']))
            ->all();

        // then
        $this->assertSame(['First', 'Third', 'Fifth'], $filtered);
    }

    /**
     * @test
     */
    public function shouldGet_first()
    {
        // when
        $first = pattern('[A-Z][a-z]+')->match('First, Second, Third')->remaining(DetailFunctions::notEquals('First'))->first();

        // then
        $this->assertSame('Second', $first);
    }

    /**
     * @test
     */
    public function shouldGet_nth()
    {
        // when
        $result = pattern('\d+(cm|mm)')->match('12cm 14mm 13cm 19cm')->remaining(DetailFunctions::notEquals('14mm'))->nth(2);

        // then
        $this->assertSame('19cm', $result);
    }

    /**
     * @test
     */
    public function shouldBe_Countable()
    {
        // given
        $pattern = pattern('\w+')->match('One, two, three')->stream()->filter(DetailFunctions::notEquals('two'));
        $this->assertIsNotArray($pattern);

        // when
        $size = count($pattern);

        // then
        $this->assertSame(2, $size);
    }

    /**
     * @test
     */
    public function shouldGet_offset_getIterator()
    {
        // when
        $iterator = pattern('\w+')->match('One, two, three')
            ->remaining(DetailFunctions::notEquals('two'))
            ->offsets()
            ->getIterator();

        // then
        $this->assertSame([0, 10], iterator_to_array($iterator));
    }

    /**
     * @test
     */
    public function shouldGet_asInt_all()
    {
        // given
        $subject = "I'll have two number 9s, a number 9 large, a number 6 with extra dip, a number 7, two number 45s, one with cheese, and a large soda.";

        // when
        $integers = pattern('\d+')->match($subject)->remaining(Functions::oneOf(['6', '45']))->asInt()->all();

        // then
        $this->assertSame([6, 45], $integers);
    }

    /**
     * @test
     */
    public function shouldForEachGroup_acceptKey()
    {
        // given
        $arguments = [];

        // when
        pattern('(\w+)')->match('Foo, Bar, Cat, Dur')
            ->remaining(Functions::oneOf(['Foo', 'Cat', 'Dur']))
            ->group(1)
            ->forEach(function (string $argument, int $index) use (&$arguments) {
                $arguments[$argument] = $index;
            });

        // then
        $this->assertSame(['Foo' => 0, 'Cat' => 1, 'Dur' => 2], $arguments);
    }
}

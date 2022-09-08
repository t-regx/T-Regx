<?php
namespace Test\Feature\CleanRegex\Replace\Details;

use PHPUnit\Framework\TestCase;
use Test\Utils\DetailFunctions;
use TRegx\CleanRegex\Match\Detail;
use TRegx\CleanRegex\Pattern;

/**
 * @coversNothing
 */
class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldGetText()
    {
        /**
         * @var Detail $detail
         */
        // given
        Pattern::of('\w+')->replace('Quizzaciously')->callback(DetailFunctions::outLast($detail, ''));
        // when
        $text = $detail->text();
        // then
        $this->assertSame('Quizzaciously', $text);
    }

    /**
     * @test
     */
    public function shouldGetLength()
    {
        /**
         * @var Detail $detail
         */
        // given
        Pattern::of('.+')->replace('12.14€')->callback(DetailFunctions::outLast($detail, ''));
        // when, then
        $this->assertSame(6, $detail->length());
        $this->assertSame(8, $detail->byteLength());
    }

    /**
     * @test
     */
    public function shouldBeUsingDuplicateName()
    {
        /**
         * @var Detail $detail
         */
        // given
        Pattern::of('(?<name>One),(?<name>Two)', 'J')->replace('One,Two')->callback(DetailFunctions::outLast($detail, ''));
        // when, then
        $this->assertSame('One', $detail->get('name'));
    }

    /**
     * @test
     */
    public function shouldGetGroupNames()
    {
        /**
         * @var Detail $detail
         */
        // given
        Pattern::of('(One)(?<name>Two)()')->replace('OneTwo')->callback(DetailFunctions::outLast($detail, ''));
        // when
        $groupNames = $detail->groupNames();
        // then
        $this->assertSame([null, 'name', null], $groupNames);
    }

    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        Pattern::of('"[ \w]+"')
            ->replace('"Tyler Durden", "Marla Singer"')
            ->callback(DetailFunctions::out($detail, ''));
        // when
        $all = $detail->all();
        // then
        $this->assertSame(['"Tyler Durden"', '"Marla Singer"'], $all);
    }

    /**
     * @test
     */
    public function shouldGetIndexFirst()
    {
        /**
         * @var Detail $detail
         */
        // given
        Pattern::of('\w+')->replace('Foo, Bar')->callback(DetailFunctions::outLast($detail, ''));
        // when
        $index = $detail->index();
        // then
        $this->assertSame(1, $index);
    }

    /**
     * @test
     */
    public function shouldGetIndex()
    {
        /**
         * @var Detail $detail
         */
        // given
        Pattern::of('\w+')->replace('Foo, Bar, Cat')->callback(DetailFunctions::outLast($detail, ''));
        // when
        $index = $detail->index();
        // then
        $this->assertSame(2, $index);
    }

    /**
     * @test
     */
    public function shouldGetOffset()
    {
        /**
         * @var Detail $detail
         */
        // given
        Pattern::of('Łódź')->replace('€--Łódź')->callback(DetailFunctions::outLast($detail, ''));
        // when, then
        $this->assertSame(3, $detail->offset());
        $this->assertSame(5, $detail->byteOffset());
    }

    /**
     * @test
     */
    public function shouldGetTail()
    {
        /**
         * @var Detail $detail
         */
        // given
        Pattern::of('Łódź')->replace('€--Łódź')->callback(DetailFunctions::outLast($detail, ''));
        // when, then
        $this->assertSame(7, $detail->tail());
        $this->assertSame(12, $detail->byteTail());
    }

    /**
     * @test
     */
    public function shouldGetToInt()
    {
        /**
         * @var Detail $detail
         */
        // given
        Pattern::of('\w+')->replace('14')->callback(DetailFunctions::outLast($detail, ''));
        // when
        $int = $detail->toInt();
        // then
        $this->assertSame(14, $int);
    }

    /**
     * @test
     */
    public function shouldBeInt()
    {
        /**
         * @var Detail $detail
         */
        // given
        Pattern::of('\w+')->replace('14')->callback(DetailFunctions::outLast($detail, ''));
        // when, then
        $this->assertTrue($detail->isInt());
    }

    /**
     * @test
     */
    public function shouldNotBeInt()
    {
        /**
         * @var Detail $detail
         */
        // given
        Pattern::of('\w+')->replace('14a')->callback(DetailFunctions::outLast($detail, ''));
        // when, then
        $this->assertFalse($detail->isInt());
    }

    /**
     * @test
     */
    public function shouldGroupBeMatched()
    {
        /**
         * @var Detail $detail
         */
        // given
        Pattern::of('(One)?(Two)')->replace('Two')->callback(DetailFunctions::outLast($detail, ''));
        // when, then
        $this->assertTrue($detail->matched(0));
        $this->assertFalse($detail->matched(1));
        $this->assertTrue($detail->matched(2));
    }

    /**
     * @test
     */
    public function shouldGroupBeMatchedSecond()
    {
        /**
         * @var Detail $detail
         */
        // given
        Pattern::of('(One)(Two)?')->replace('One')->callback(DetailFunctions::outLast($detail, ''));
        // when, then
        $this->assertTrue($detail->matched(0));
        $this->assertTrue($detail->matched(1));
        $this->assertFalse($detail->matched(2));
    }

    /**
     * @test
     */
    public function shouldHaveGroup()
    {
        /**
         * @var Detail $detail
         */
        // given
        Pattern::of('(?<Foo>\w+)')->replace('Quizzaciously')->callback(DetailFunctions::outLast($detail, ''));
        // when, then
        $this->assertTrue($detail->groupExists('Foo'));
        $this->assertFalse($detail->groupExists('missing'));
    }

    /**
     * @test
     */
    public function shouldGet_toString()
    {
        /**
         * @var Detail $detail
         */
        // given
        Pattern::of('\w+')->replace('Quizzaciously')->callback(DetailFunctions::outLast($detail, ''));
        // when
        $text = (string)$detail;
        // then
        $this->assertSame('Quizzaciously', $text);
    }
}

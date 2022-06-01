<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details;

use PHPUnit\Framework\TestCase;
use Test\Utils\DetailFunctions;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Replace\Details\ReplaceDetail
 */
class ReplaceDetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetText()
    {
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
        // given
        Pattern::of('.+')->replace('12.14€')->callback(DetailFunctions::outLast($detail, ''));
        // when, then
        $this->assertSame(6, $detail->length());
        $this->assertSame(8, $detail->byteLength());
    }

    /**
     * @test
     */
    public function shouldGetModifiedSubject()
    {
        // given
        Pattern::of('\w+')->replace('Foo, Bar')->callback(DetailFunctions::outLast($detail, '_'));
        // when, then
        $this->assertSame('Foo, Bar', $detail->subject());
        $this->assertSame('_, Bar', $detail->modifiedSubject());
    }

    /**
     * @test
     */
    public function shouldGetModifiedOffset()
    {
        // given
        Pattern::of('[\w€]+')->replace('Foo€, Bar')->callback(DetailFunctions::outLast($detail, '€'));
        // when, then
        $this->assertSame(6, $detail->offset());
        $this->assertSame(3, $detail->modifiedOffset());
    }

    /**
     * @test
     */
    public function shouldGetByteModifiedOffset()
    {
        // given
        Pattern::of('[\w€]+')->replace('Foo€, Bar')->callback(DetailFunctions::outLast($detail, '€'));
        // when, then
        $this->assertSame(8, $detail->byteOffset());
        $this->assertSame(5, $detail->byteModifiedOffset());
    }

    /**
     * @test
     */
    public function shouldBeUsingDuplicateName()
    {
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
        Pattern::of('\w+')->replace('Foo, Bar, Cat')->callback(DetailFunctions::outLast($detail, '€'));
        // when
        $all = $detail->all();
        // then
        $this->assertSame(['Foo', 'Bar', 'Cat'], $all);
    }

    /**
     * @test
     */
    public function shouldGetIndexFirst()
    {
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
    public function shouldGetLimit()
    {
        // given
        Pattern::of('\w+')->replace('Foo, Bar, Cat')->only(42)->callback(DetailFunctions::outLast($detail, ''));
        // when
        $limit = $detail->limit();
        // then
        $this->assertSame(42, $limit);
    }

    /**
     * @test
     */
    public function shouldGetLimitAll()
    {
        // given
        Pattern::of('\w+')->replace('Foo, Bar, Cat')->callback(DetailFunctions::outLast($detail, ''));
        // when
        $limit = $detail->limit();
        // then
        $this->assertSame(-1, $limit);
    }

    /**
     * @test
     */
    public function shouldGetTail()
    {
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
        // given
        Pattern::of('\w+')->replace('Quizzaciously')->callback(DetailFunctions::outLast($detail, ''));
        // when
        $text = (string)$detail;
        // then
        $this->assertSame('Quizzaciously', $text);
    }
}

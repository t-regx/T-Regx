<?php
namespace Test\Feature\CleanRegex\Replace\callback;

use PHPUnit\Framework\TestCase;
use Test\Utils\DetailFunctions;
use TRegx\CleanRegex\Pattern;
use TRegx\CleanRegex\Replace\Details\ReplaceDetail;

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
        /**
         * @var ReplaceDetail $detail
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
         * @var ReplaceDetail $detail
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
    public function shouldGetModifiedSubject()
    {
        /**
         * @var ReplaceDetail $detail
         */
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
        /**
         * @var ReplaceDetail $detail
         */
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
        /**
         * @var ReplaceDetail $detail
         */
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
        /**
         * @var ReplaceDetail $detail
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
         * @var ReplaceDetail $detail
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
        /**
         * @var ReplaceDetail $detail
         */
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
        /**
         * @var ReplaceDetail $detail
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
         * @var ReplaceDetail $detail
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
    public function shouldGetLimit()
    {
        /**
         * @var ReplaceDetail $detail
         */
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
        /**
         * @var ReplaceDetail $detail
         */
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
        /**
         * @var ReplaceDetail $detail
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
         * @var ReplaceDetail $detail
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
         * @var ReplaceDetail $detail
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
         * @var ReplaceDetail $detail
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
         * @var ReplaceDetail $detail
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
         * @var ReplaceDetail $detail
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
         * @var ReplaceDetail $detail
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
         * @var ReplaceDetail $detail
         */
        // given
        Pattern::of('\w+')->replace('Quizzaciously')->callback(DetailFunctions::outLast($detail, ''));
        // when
        $text = (string)$detail;
        // then
        $this->assertSame('Quizzaciously', $text);
    }
}

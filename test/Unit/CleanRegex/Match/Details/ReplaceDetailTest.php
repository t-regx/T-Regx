<?php
namespace Test\Unit\TRegx\CleanRegex\Match\Details;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Model\Match\ThrowEntry;
use Test\Fakes\CleanRegex\Match\Details\GroupDetail;
use Test\Fakes\CleanRegex\Match\Details\TextDetail;
use Test\Fakes\CleanRegex\Match\Details\ThrowDetail;
use Test\Fakes\CleanRegex\Match\Details\UserDataDetail;
use Test\Fakes\CleanRegex\Replace\Details\ConstantModification;
use Test\Fakes\CleanRegex\Replace\Details\ThrowModification;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\DuplicateName;
use TRegx\CleanRegex\Replace\Details\Modification;
use TRegx\CleanRegex\Replace\Details\ReplaceDetail;

/**
 * @covers \TRegx\CleanRegex\Replace\Details\ReplaceDetail
 */
class ReplaceDetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetModifiedSubject()
    {
        // given
        $detail = new ReplaceDetail(new ThrowDetail(), new Modification(new ThrowEntry(), 'subject', 0));

        // when
        $subject = $detail->modifiedSubject();

        // then
        $this->assertSame('subject', $subject);
    }

    /**
     * @test
     */
    public function shouldGetModifiedOffset()
    {
        // given
        $detail = new ReplaceDetail(new ThrowDetail(), new ConstantModification(11, 13));

        // when
        $offset = $detail->modifiedOffset();

        // then
        $this->assertSame(11, $offset);
    }

    /**
     * @test
     */
    public function shouldGetByteModifiedOffset()
    {
        // given
        $detail = new ReplaceDetail(new ThrowDetail(), new ConstantModification(10, 12));

        // when
        $offset = $detail->byteModifiedOffset();

        // then
        $this->assertSame(12, $offset);
    }

    /**
     * @test
     */
    public function shouldBeUsingDuplicateName()
    {
        // given
        $input = $this->createMock(DuplicateName::class);
        $detail = new ReplaceDetail($this->detail('usingDuplicateName', $input), new ThrowModification());

        // when
        $output = $detail->usingDuplicateName();

        // then
        $this->assertSame($input, $output);
    }

    /**
     * @test
     */
    public function shouldGetSubject()
    {
        // given
        $detail = new ReplaceDetail($this->detail('subject', 'foo bar'), new ThrowModification());

        // when
        $subject = $detail->subject();

        // then
        $this->assertSame('foo bar', $subject);
    }

    /**
     * @test
     */
    public function shouldGetGroupNames()
    {
        // given
        $detail = new ReplaceDetail($this->detail('groupNames', ['one', 'two']), new ThrowModification());

        // when
        $groupNames = $detail->groupNames();

        // then
        $this->assertSame(['one', 'two'], $groupNames);
    }

    /**
     * @test
     */
    public function shouldGetText()
    {
        // given
        $detail = new ReplaceDetail((new TextDetail('bar')), new ThrowModification());

        // when
        $text = $detail->text();

        // then
        $this->assertSame('bar', $text);
    }

    /**
     * @test
     */
    public function shouldGetTextLength()
    {
        // given
        $detail = new ReplaceDetail($this->detail('textLength', 11), new ThrowModification());

        // when
        $length = $detail->textLength();

        // then
        $this->assertSame(11, $length);
    }

    /**
     * @test
     */
    public function shouldGetTextByteLength()
    {
        // given
        $detail = new ReplaceDetail($this->detail('textByteLength', 6), new ThrowModification());

        // when
        $length = $detail->textByteLength();

        // then
        $this->assertSame(6, $length);
    }

    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $detail = new ReplaceDetail($this->detail('all', ['all', 'one', 'two']), new ThrowModification());

        // when
        $all = $detail->all();

        // then
        $this->assertSame(['all', 'one', 'two'], $all);
    }

    /**
     * @test
     */
    public function shouldGetIndex()
    {
        // given
        $detail = new ReplaceDetail($this->detail('index', 14), new ThrowModification());

        // when
        $index = $detail->index();

        // then
        $this->assertSame(14, $index);
    }

    /**
     * @test
     */
    public function shouldGetLimit()
    {
        // given
        $detail = new ReplaceDetail($this->detail('limit', 14), new ThrowModification());

        // when
        $limit = $detail->limit();

        // then
        $this->assertSame(14, $limit);
    }

    /**
     * @test
     */
    public function shouldGetOffset()
    {
        // given
        $detail = new ReplaceDetail($this->detail('offset', 12), new ThrowModification());

        // when
        $offset = $detail->offset();

        // then
        $this->assertSame(12, $offset);
    }

    /**
     * @test
     */
    public function shouldGetTail()
    {
        // given
        $detail = new ReplaceDetail($this->detail('tail', 15), new ThrowModification());

        // when
        $offset = $detail->tail();

        // then
        $this->assertSame(15, $offset);
    }

    /**
     * @test
     */
    public function shouldGetByteOffset()
    {
        // given
        $detail = new ReplaceDetail($this->detail('byteOffset', 14), new ThrowModification());

        // when
        $byteOffset = $detail->byteOffset();

        // then
        $this->assertSame(14, $byteOffset);
    }

    /**
     * @test
     */
    public function shouldGetByteTail()
    {
        // given
        $detail = new ReplaceDetail($this->detail('byteTail', 16), new ThrowModification());

        // when
        $byteOffset = $detail->byteTail();

        // then
        $this->assertSame(16, $byteOffset);
    }

    /**
     * @test
     */
    public function shouldGetUserData()
    {
        // given
        $detail = new ReplaceDetail($this->detail('getUserData', 14), new ThrowModification());

        // when
        $userData = $detail->getUserData();

        // then
        $this->assertSame(14, $userData);
    }

    /**
     * @test
     */
    public function shouldGetToInt()
    {
        // given
        $detail = new ReplaceDetail($this->detail('toInt', 14), new ThrowModification());

        // when
        $int = $detail->toInt();

        // then
        $this->assertSame(14, $int);
    }

    /**
     * @test
     */
    public function shouldGetIsInt()
    {
        // given
        $detail = new ReplaceDetail($this->detail('isInt', true), new ThrowModification());

        // when
        $isInt = $detail->isInt();

        // then
        $this->assertTrue($isInt);
    }

    /**
     * @test
     */
    public function shouldSetUserData()
    {
        // given
        $userData = new UserData();
        $detail = new ReplaceDetail(new UserDataDetail($userData), new ThrowModification());

        // when
        $detail->setUserData('14');

        // then
        $this->assertSame('14', $userData->get($detail));
    }

    /**
     * @test
     */
    public function shouldGroupBeMatched()
    {
        // given

        $detail = new ReplaceDetail(new GroupDetail(['foo' => true]), new ThrowModification());

        // when
        $matched = $detail->matched('foo');

        // then
        $this->assertTrue($matched);
    }

    /**
     * @test
     */
    public function shouldGroupNotBeMatched()
    {
        // given

        $detail = new ReplaceDetail(new GroupDetail(['bar' => false]), new ThrowModification());

        // when
        $matched = $detail->matched('bar');

        // then
        $this->assertFalse($matched);
    }

    /**
     * @test
     */
    public function shouldHaveGroup()
    {
        // given

        $detail = new ReplaceDetail(new GroupDetail(['group' => false]), new ThrowModification());

        // when
        $hasGroup = $detail->hasGroup('group');

        // then
        $this->assertTrue($hasGroup);
    }

    /**
     * @test
     */
    public function shouldNotHaveGroup()
    {
        // given

        $detail = new ReplaceDetail(new GroupDetail([]), new ThrowModification());

        // when
        $hasGroup = $detail->hasGroup('group');

        // then
        $this->assertFalse($hasGroup);
    }

    /**
     * @test
     */
    public function shouldGet_toString()
    {
        // given
        $detail = new ReplaceDetail($this->detail('__toString', 'text'), new ThrowModification());

        // when
        $text = (string)$detail;

        // then
        $this->assertSame('text', $text);
    }

    private function detail(string $method, $returns): Detail
    {
        $mock = $this->createMock(Detail::class);
        $mock->method($method)->willReturn($returns);
        return $mock;
    }
}

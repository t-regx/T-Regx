<?php
namespace Test\Unit\TRegx\CleanRegex\Match\Details;

use PHPUnit\Framework\TestCase;
use Test\Utils\Impl\GroupDetail;
use Test\Utils\Impl\TextDetail;
use Test\Utils\Impl\ThrowDetail;
use Test\Utils\Impl\UserDataDetail;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\DuplicateName;
use TRegx\CleanRegex\Match\Details\Groups\IndexedGroups;
use TRegx\CleanRegex\Match\Details\Groups\NamedGroups;
use TRegx\CleanRegex\Match\Details\ReplaceDetail;

/**
 * @covers \TRegx\CleanRegex\Match\Details\ReplaceDetail
 */
class ReplaceDetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetModifiedSubject()
    {
        // given
        $detail = new ReplaceDetail(new ThrowDetail(), 0, 'subject');

        // when
        $subject = $detail->modifiedSubject();

        // then
        $this->assertSame('subject', $subject);
    }

    /**
     * @test
     */
    public function shouldGetByteModifiedOffset()
    {
        // given
        $detail = new ReplaceDetail($this->detail('byteOffset', 14), 6, '');

        // when
        $offset = $detail->byteModifiedOffset();

        // then
        $this->assertSame(20, $offset);
    }

    /**
     * @test
     */
    public function shouldBeUsingDuplicateName()
    {
        // given
        $input = $this->createMock(DuplicateName::class);
        $detail = new ReplaceDetail($this->detail('usingDuplicateName', $input), 0, '');

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
        $detail = new ReplaceDetail($this->detail('subject', 'foo bar'), 0, '');

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
        $detail = new ReplaceDetail($this->detail('groupNames', ['one', 'two']), 0, '');

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
        $detail = new ReplaceDetail(new TextDetail('bar'), 0, '');

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
        $detail = new ReplaceDetail($this->detail('textLength', 11), 0, '');

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
        $detail = new ReplaceDetail($this->detail('textByteLength', 6), 0, '');

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
        $detail = new ReplaceDetail($this->detail('all', ['all', 'one', 'two']), 0, '');

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
        $detail = new ReplaceDetail($this->detail('index', 14), 0, '');

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
        $detail = new ReplaceDetail($this->detail('limit', 14), 0, '');

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
        $detail = new ReplaceDetail($this->detail('offset', 12), 0, '');

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
        $detail = new ReplaceDetail($this->detail('tail', 15), 0, '');

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
        $detail = new ReplaceDetail($this->detail('byteOffset', 14), 0, '');

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
        $detail = new ReplaceDetail($this->detail('byteTail', 16), 0, '');

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
        $detail = new ReplaceDetail($this->detail('getUserData', 14), 0, '');

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
        $detail = new ReplaceDetail($this->detail('toInt', 14), 0, '');

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
        $detail = new ReplaceDetail($this->detail('isInt', true), 0, '');

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
        $detail = new ReplaceDetail(new UserDataDetail($userData), 0, '');

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

        $detail = new ReplaceDetail(new GroupDetail(['foo' => true]), 0, '');

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

        $detail = new ReplaceDetail(new GroupDetail(['bar' => false]), 0, '');

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

        $detail = new ReplaceDetail(new GroupDetail(['group' => false]), 0, '');

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

        $detail = new ReplaceDetail(new GroupDetail([]), 0, '');

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
        $detail = new ReplaceDetail($this->detail('__toString', 'text'), 0, '');

        // when
        $text = (string)$detail;

        // then
        $this->assertSame('text', $text);
    }

    /**
     * @test
     */
    public function shouldGet_groups()
    {
        // given
        $indexed = $this->createMock(IndexedGroups::class);
        $detail = new ReplaceDetail($this->detail('groups', $indexed), 0, '');

        // when
        $groups = $detail->groups();

        // then
        $this->assertSame($indexed, $groups);
    }

    /**
     * @test
     */
    public function shouldGet_namedGroups()
    {
        // given
        $named = $this->createMock(NamedGroups::class);
        $detail = new ReplaceDetail($this->detail('namedGroups', $named), 0, '');

        // when
        $groups = $detail->namedGroups();

        // then
        $this->assertSame($named, $groups);
    }

    private function detail(string $method, $returns): Detail
    {
        $mock = $this->createMock(Detail::class);
        $mock->method($method)->willReturn($returns);
        return $mock;
    }
}

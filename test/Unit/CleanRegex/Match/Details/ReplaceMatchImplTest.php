<?php
namespace Test\Unit\TRegx\CleanRegex\Match\Details;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\DuplicateName;
use TRegx\CleanRegex\Match\Details\Groups\IndexedGroups;
use TRegx\CleanRegex\Match\Details\Groups\NamedGroups;
use TRegx\CleanRegex\Match\Details\ReplaceMatchImpl;

class ReplaceMatchImplTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGet_modifiedSubject()
    {
        // given
        $detail = new ReplaceMatchImpl($this->getMatchMock(), 0, 'subject');

        // when
        $subject = $detail->modifiedSubject();

        // then
        $this->assertSame('subject', $subject);
    }

    /**
     * @test
     */
    public function shouldUsingDuplicateName_get()
    {
        // given
        /** @var ReplaceMatchImpl $detail */
        [$detail, $detailMock] = $this->getReplaceDetailAndMock();
        $input = $this->createMock(DuplicateName::class);
        $detailMock->method('usingDuplicateName')->willReturn($input);

        // when
        $duplicateName = $detail->usingDuplicateName();

        // then
        $this->assertSame($input, $duplicateName);
    }

    /**
     * @test
     */
    public function shouldGet_modifiedOffset()
    {
        // given
        /** @var ReplaceMatchImpl $detail */
        [$detail, $detailMock] = $this->getReplaceDetailAndMock(6);
        $detailMock->method('offset')->willReturn(14);

        // when
        $offset = $detail->modifiedOffset();

        // then
        $this->assertSame(20, $offset);
    }

    /**
     * @test
     */
    public function shouldGet_subject()
    {
        // given
        $detail = $this->getDetail_mockedMethod('subject', 'subject result');

        // when
        $subject = $detail->subject();

        // then
        $this->assertSame('subject result', $subject);
    }

    /**
     * @test
     */
    public function shouldGet_groupNames()
    {
        // given
        $detail = $this->getDetail_mockedMethod('groupNames', ['one', 'two']);

        // when
        $groupNames = $detail->groupNames();

        // then
        $this->assertSame(['one', 'two'], $groupNames);
    }

    /**
     * @test
     */
    public function shouldGet_text()
    {
        // given
        $detail = $this->getDetail_mockedMethod('text', 'text result');

        // when
        $text = $detail->text();

        // then
        $this->assertSame('text result', $text);
    }

    /**
     * @test
     */
    public function shouldGet_textLength()
    {
        // given
        $detail = $this->getDetail_mockedMethod('textLength', 11);

        // when
        $length = $detail->textLength();

        // then
        $this->assertSame(11, $length);
    }

    /**
     * @test
     */
    public function shouldGet_textByteLength()
    {
        // given
        $detail = $this->getDetail_mockedMethod('textByteLength', 6);

        // when
        $length = $detail->textByteLength();

        // then
        $this->assertSame(6, $length);
    }

    /**
     * @test
     */
    public function shouldGet_all()
    {
        // given
        $detail = $this->getDetail_mockedMethod('all', ['all', 'one', 'two']);

        // when
        $all = $detail->all();

        // then
        $this->assertSame(['all', 'one', 'two'], $all);
    }

    /**
     * @test
     */
    public function shouldGet_index()
    {
        // given
        $detail = $this->getDetail_mockedMethod('index', 14);

        // when
        $index = $detail->index();

        // then
        $this->assertSame(14, $index);
    }

    /**
     * @test
     */
    public function shouldGet_limit()
    {
        // given
        $detail = $this->getDetail_mockedMethod('limit', 14);

        // when
        $limit = $detail->limit();

        // then
        $this->assertSame(14, $limit);
    }

    /**
     * @test
     */
    public function shouldGet_offset()
    {
        // given
        $detail = $this->getDetail_mockedMethod('offset', 14);

        // when
        $offset = $detail->offset();

        // then
        $this->assertSame(14, $offset);
    }

    /**
     * @test
     */
    public function shouldGet_tail()
    {
        // given
        $detail = $this->getDetail_mockedMethod('tail', 15);

        // when
        $offset = $detail->tail();

        // then
        $this->assertSame(15, $offset);
    }

    /**
     * @test
     */
    public function shouldGet_byteOffset()
    {
        // given
        $detail = $this->getDetail_mockedMethod('byteOffset', 14);

        // when
        $byteOffset = $detail->byteOffset();

        // then
        $this->assertSame(14, $byteOffset);
    }

    /**
     * @test
     */
    public function shouldGet_byteTail()
    {
        // given
        $detail = $this->getDetail_mockedMethod('byteTail', 16);

        // when
        $byteOffset = $detail->byteTail();

        // then
        $this->assertSame(16, $byteOffset);
    }

    /**
     * @test
     */
    public function shouldGet_getUserData()
    {
        // given
        $detail = $this->getDetail_mockedMethod('getUserData', 14);

        // when
        $userData = $detail->getUserData();

        // then
        $this->assertSame(14, $userData);
    }

    /**
     * @test
     */
    public function shouldGet_toInt()
    {
        // given
        $detail = $this->getDetail_mockedMethod('toInt', 14);

        // when
        $int = $detail->toInt();

        // then
        $this->assertSame(14, $int);
    }

    /**
     * @test
     */
    public function shouldGet_isInt()
    {
        // given
        $detail = $this->getDetail_mockedMethod('isInt', true);

        // when
        $isInt = $detail->isInt();

        // then
        $this->assertTrue($isInt);
    }

    /**
     * @test
     */
    public function shouldGet_setUserData()
    {
        // given
        /** @var ReplaceMatchImpl $detail */
        [$detail, $detailMock] = $this->getReplaceDetailAndMock();

        // expects
        $detailMock->expects($this->once())
            ->method('setUserData')
            ->with($this->equalTo('14'));

        // when
        $detail->setUserData('14');
    }

    /**
     * @test
     */
    public function shouldGet_matched()
    {
        // given
        /** @var ReplaceMatchImpl $detail */
        /** @var MockObject $detailMock */
        [$detail, $detailMock] = $this->getReplaceDetailAndMock();

        // expects
        $detailMock
            ->method('matched')
            ->willReturn(true);

        $detailMock->expects($this->once())
            ->method('matched')
            ->with($this->equalTo('group'));

        // when
        $matched = $detail->matched('group');

        // then
        $this->assertTrue($matched);
    }

    /**
     * @test
     */
    public function shouldGet_hasGroup()
    {
        // given
        /** @var ReplaceMatchImpl $detail */
        /** @var MockObject $detailMock */
        [$detail, $detailMock] = $this->getReplaceDetailAndMock();

        // expects
        $detailMock
            ->method('hasGroup')
            ->willReturn(true);

        $detailMock->expects($this->once())
            ->method('hasGroup')
            ->with($this->equalTo('group'));

        // when
        $hasGroup = $detail->hasGroup('group');

        // then
        $this->assertTrue($hasGroup);
    }

    /**
     * @test
     */
    public function shouldGet_toString()
    {
        // given
        $detail = $this->getDetail_mockedMethod('__toString', 'text');

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
        /** @var ReplaceMatchImpl $detail */
        /** @var MockObject $detailMock */
        [$detail, $detailMock] = $this->getReplaceDetailAndMock();

        // expects
        $indexedGroups = $this->createMock(IndexedGroups::class);
        $indexedGroups
            ->method('texts')
            ->willReturn(['one', 'two']);

        $detailMock
            ->method('groups')
            ->willReturn($indexedGroups);

        // when
        $groups = $detail->groups();

        // then
        $this->assertSame(['one', 'two'], $groups->texts());
    }

    /**
     * @test
     */
    public function shouldGet_namedGroups()
    {
        // given
        /** @var ReplaceMatchImpl $detail */
        /** @var MockObject $detailMock */
        [$detail, $detailMock] = $this->getReplaceDetailAndMock();

        // expects
        $indexedGroups = $this->createMock(NamedGroups::class);
        $indexedGroups
            ->method('texts')
            ->willReturn(['first' => 'one', 'second' => 'two']);

        $detailMock
            ->method('namedGroups')
            ->willReturn($indexedGroups);

        // when
        $groups = $detail->namedGroups();

        // then
        $this->assertSame(['first' => 'one', 'second' => 'two'], $groups->texts());
    }

    private function getDetail_mockedMethod(string $method, $result): ReplaceMatchImpl
    {
        [$detail, $mockMatch] = $this->getReplaceDetailAndMock();
        $mockMatch->method($method)->willReturn($result);
        return $detail;
    }

    private function getReplaceDetailAndMock(int $offset = 0, string $subject = ''): array
    {
        $mock = $this->getMatchMock();
        $detail = new ReplaceMatchImpl($mock, $offset, $subject);
        return [$detail, $mock];
    }

    private function getMatchMock(): Detail
    {
        return $this->createMock(Detail::class);
    }
}

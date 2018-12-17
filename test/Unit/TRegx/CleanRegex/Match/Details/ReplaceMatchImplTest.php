<?php
namespace Test\Unit\TRegx\CleanRegex\Match\Details;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Groups\IndexedGroups;
use TRegx\CleanRegex\Match\Details\Groups\NamedGroups;
use TRegx\CleanRegex\Match\Details\Match;
use TRegx\CleanRegex\Match\Details\ReplaceMatchImpl;

class ReplaceMatchImplTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGet_modifiedSubject()
    {
        // given
        $match = new ReplaceMatchImpl($this->getMatchMock(), 0, 'subject');

        // when
        $subject = $match->modifiedSubject();

        // then
        $this->assertEquals('subject', $subject);
    }

    /**
     * @test
     */
    public function shouldGet_modifiedOffset()
    {
        // given
        /** @var ReplaceMatchImpl $match */
        [$match, $mockMatch] = $this->getReplaceMatchAndMock(6);
        $mockMatch->method('offset')->willReturn(14);

        // when
        $offset = $match->modifiedOffset();

        // then
        $this->assertEquals(20, $offset);
    }

    /**
     * @test
     */
    public function shouldGet_subject()
    {
        // given
        $match = $this->getMatch_mockedMethod('subject', 'subject result');

        // when
        $offset = $match->subject();

        // then
        $this->assertEquals('subject result', $offset);
    }

    /**
     * @test
     */
    public function shouldGet_groupNames()
    {
        // given
        $match = $this->getMatch_mockedMethod('groupNames', ['one', 'two']);

        // when
        $offset = $match->groupNames();

        // then
        $this->assertEquals(['one', 'two'], $offset);
    }

    /**
     * @test
     */
    public function shouldGet_text()
    {
        // given
        $match = $this->getMatch_mockedMethod('text', 'text result');

        // when
        $offset = $match->text();

        // then
        $this->assertEquals('text result', $offset);
    }

    /**
     * @test
     */
    public function shouldGet_all()
    {
        // given
        $match = $this->getMatch_mockedMethod('all', ['all', 'one', 'two']);

        // when
        $offset = $match->all();

        // then
        $this->assertEquals(['all', 'one', 'two'], $offset);
    }

    /**
     * @test
     */
    public function shouldGet_index()
    {
        // given
        $match = $this->getMatch_mockedMethod('index', 14);

        // when
        $offset = $match->index();

        // then
        $this->assertEquals(14, $offset);
    }

    /**
     * @test
     */
    public function shouldGet_limit()
    {
        // given
        $match = $this->getMatch_mockedMethod('limit', 14);

        // when
        $offset = $match->limit();

        // then
        $this->assertEquals(14, $offset);
    }

    /**
     * @test
     */
    public function shouldGet_offset()
    {
        // given
        $match = $this->getMatch_mockedMethod('offset', 14);

        // when
        $offset = $match->offset();

        // then
        $this->assertEquals(14, $offset);
    }

    /**
     * @test
     */
    public function shouldGet_byteOffset()
    {
        // given
        $match = $this->getMatch_mockedMethod('byteOffset', 14);

        // when
        $offset = $match->byteOffset();

        // then
        $this->assertEquals(14, $offset);
    }

    /**
     * @test
     */
    public function shouldGet_getUserData()
    {
        // given
        $match = $this->getMatch_mockedMethod('getUserData', 14);

        // when
        $offset = $match->getUserData();

        // then
        $this->assertEquals(14, $offset);
    }

    /**
     * @test
     */
    public function shouldGet_setUserData()
    {
        // given
        /** @var ReplaceMatchImpl $match */
        [$match, $mockMatch] = $this->getReplaceMatchAndMock();

        // expects
        $mockMatch->expects($this->once())
            ->method('setUserData')
            ->with($this->equalTo('14'));

        // when
        $match->setUserData('14');
    }

    /**
     * @test
     */
    public function shouldGet_matched()
    {
        // given
        /** @var ReplaceMatchImpl $match */
        /** @var MockObject $mockMatch */
        [$match, $mockMatch] = $this->getReplaceMatchAndMock();

        // expects
        $mockMatch
            ->method('matched')
            ->willReturn(true);

        $mockMatch->expects($this->once())
            ->method('matched')
            ->with($this->equalTo('group'));

        // when
        $matched = $match->matched('group');

        // then
        $this->assertTrue($matched);
    }

    /**
     * @test
     */
    public function shouldGet_hasGroup()
    {
        // given
        /** @var ReplaceMatchImpl $match */
        /** @var MockObject $mockMatch */
        [$match, $mockMatch] = $this->getReplaceMatchAndMock();

        // expects
        $mockMatch
            ->method('hasGroup')
            ->willReturn(true);

        $mockMatch->expects($this->once())
            ->method('hasGroup')
            ->with($this->equalTo('group'));

        // when
        $hasGroup = $match->hasGroup('group');

        // then
        $this->assertTrue($hasGroup);
    }

    /**
     * @test
     */
    public function shouldGet_toString()
    {
        // given
        $match = $this->getMatch_mockedMethod('__toString', 'text');

        // when
        $text = (string)$match;

        // then
        $this->assertEquals('text', $text);
    }

    /**
     * @test
     */
    public function shouldGet_groups()
    {
        // given
        /** @var ReplaceMatchImpl $match */
        /** @var MockObject $mockMatch */
        [$match, $mockMatch] = $this->getReplaceMatchAndMock();

        // expects
        $indexedGroups = $this->createMock(IndexedGroups::class);
        $indexedGroups
            ->method('texts')
            ->willReturn(['one', 'two']);

        $mockMatch
            ->method('groups')
            ->willReturn($indexedGroups);

        // when
        $groups = $match->groups();

        // then
        $texts = $groups->texts();
        $this->assertEquals(['one', 'two'], $texts);
    }

    /**
     * @test
     */
    public function shouldGet_namedGroups()
    {
        // given
        /** @var ReplaceMatchImpl $match */
        /** @var MockObject $mockMatch */
        [$match, $mockMatch] = $this->getReplaceMatchAndMock();

        // expects
        $indexedGroups = $this->createMock(NamedGroups::class);
        $indexedGroups
            ->method('texts')
            ->willReturn(['first' => 'one', 'second' => 'two']);

        $mockMatch
            ->method('namedGroups')
            ->willReturn($indexedGroups);

        // when
        $groups = $match->namedGroups();

        // then
        $texts = $groups->texts();
        $this->assertEquals(['first' => 'one', 'second' => 'two'], $texts);
    }

    private function getMatch_mockedMethod(string $method, $result): ReplaceMatchImpl
    {
        [$match, $mockMatch] = $this->getReplaceMatchAndMock();
        $mockMatch->method($method)->willReturn($result);
        return $match;
    }

    private function getReplaceMatchAndMock(int $offset = 0, string $subject = ''): array
    {
        $mock = $this->getMatchMock();
        $match = new ReplaceMatchImpl($mock, $offset, $subject);
        return [$match, $mock];
    }

    /**
     * @return Match|MockObject
     */
    private function getMatchMock(): Match
    {
        /** @var Match|MockObject $mock */
        $mock = $this->createMock(Match::class);
        return $mock;
    }
}

<?php
namespace Test\Integration\TRegx\CleanRegex\Match\Details;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\SubjectableImpl;
use TRegx\CleanRegex\Match\Details\LazyMatchImpl;

class LazyMatchImplTest extends TestCase
{
    /**
     * @test
     */
    public function shouldText()
    {
        // given
        $match = $this->match();

        // when
        $result = $match->text();

        // then
        $this->assertEquals('word', $result);
    }

    /**
     * @test
     */
    public function shouldText_castToString()
    {
        // given
        $match = $this->match();

        // when
        $result = (string)$match;

        // then
        $this->assertEquals('word', $result);
    }

    /**
     * @test
     */
    public function shouldOffset()
    {
        // given
        $match = $this->match();

        // when
        $result = $match->offset();

        // then
        $this->assertEquals(6, $result);
    }

    /**
     * @test
     */
    public function shouldLimit()
    {
        // given
        $match = $this->match();

        // when
        $result = $match->limit();

        // then
        $this->assertEquals(14, $result);
    }

    /**
     * @test
     */
    public function shouldIndex()
    {
        // given
        $match = $this->matchWithIndex('\w+', 'One, two, three', 2);

        // when
        $text = $match->text();
        $index = $match->index();

        // then
        $this->assertEquals('three', $text);
        $this->assertEquals(2, $index);
    }

    /**
     * @test
     */
    public function shouldSubject()
    {
        // given
        $match = $this->match();

        // when
        $result = $match->subject();

        // then
        $this->assertEquals('Word: word two three', $result);
    }

    /**
     * @test
     */
    public function shouldAll()
    {
        // given
        $match = $this->match();

        // when
        $result = $match->all();

        // then
        $this->assertEquals(['word', 'two', 'three'], $result);
    }

    /**
     * @test
     */
    public function shouldGetGroupNames()
    {
        // given
        $match = $this->match('!(?<first>one)(?<second>two)!', '!onetwo!');

        // when
        $result = $match->groupNames();

        // then
        $this->assertEquals(['first', 'second'], $result);
    }

    /**
     * @test
     */
    public function shouldGetGroup()
    {
        // given
        $match = $this->match('!(?<first>one)!', '!one!');

        // when
        $result = $match->group('first')->text();

        // then
        $this->assertEquals('one', $result);
    }

    /**
     * @test
     */
    public function shouldGetGroups()
    {
        // given
        $match = $this->match('(one)(two)?', 'one');

        // when
        $result = $match->groups()->texts();

        // then
        $this->assertEquals(['one', null], $result);
    }

    /**
     * @test
     */
    public function shouldGetUserData()
    {
        // given
        $match = $this->match();

        // when
        $match->setUserData('welcome');
        $result = $match->getUserData();

        // then
        $this->assertEquals('welcome', $result);
    }

    private function match(string $pattern = '\b[a-z]+', string $subject = 'Word: word two three'): LazyMatchImpl
    {
        return $this->matchWithIndex($pattern, $subject, 0);
    }

    private function matchWithIndex(string $pattern, string $subject, int $index): LazyMatchImpl
    {
        $pattern = new InternalPattern($pattern);
        $subject = new SubjectableImpl($subject);
        return new LazyMatchImpl($pattern, $subject, $index, 14, new ApiBase($pattern, $subject->getSubject(), new UserData()));
    }
}

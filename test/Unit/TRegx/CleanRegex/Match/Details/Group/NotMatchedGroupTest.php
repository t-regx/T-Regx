<?php
namespace Test\Unit\TRegx\CleanRegex\Match\Details\Group;

use InvalidArgumentException;
use TRegx\CleanRegex\Exception\CleanRegex\GroupNotMatchedException;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;
use TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\NotMatched;

class NotMatchedGroupTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetText()
    {
        // given
        $matchGroup = $this->matchGroup();

        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call text() for group 'first', but group was not matched at all");

        // when
        $matchGroup->text();
    }

    /**
     * @test
     */
    public function shouldMatch()
    {
        // given
        $matchGroup = $this->matchGroup();

        // when
        $matches = $matchGroup->matches();

        // then
        $this->assertFalse($matches);
    }

    /**
     * @test
     */
    public function shouldGetOffset()
    {
        // given
        $matchGroup = $this->matchGroup();

        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call offset() for group 'first', but group was not matched at all");

        // when
        $matchGroup->offset();
    }

    /**
     * @test
     */
    public function shouldGetName()
    {
        // given
        $matchGroup = $this->matchGroup();

        // when
        $name = $matchGroup->name();

        // then
        $this->assertEquals('first', $name);
    }

    /**
     * @test
     */
    public function shouldGetIndex()
    {
        // given
        $matchGroup = $this->matchGroup();

        // when
        $index = $matchGroup->index();

        // then
        $this->assertEquals(1, $index);
    }

    /**
     * @test
     */
    public function shouldCastToString()
    {
        // given
        $matchGroup = $this->matchGroup();

        // when
        $text = (string)$matchGroup;

        // then
        $this->assertEquals('', $text);
    }

    /**
     * @test
     */
    public function shouldControlMatched_orThrow()
    {
        // given
        $matchGroup = $this->matchGroup();

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Expected to get group 'first', but group was not matched at all");

        // when
        $matchGroup->orThrow(InvalidArgumentException::class);
    }

    /**
     * @test
     */
    public function shouldControlMatched_orElse()
    {
        // given
        $matchGroup = $this->matchGroup();

        // when
        $orElse = $matchGroup->orElse(function (NotMatched $notMatched) {
            return $notMatched->subject();
        });

        // then
        $this->assertEquals('My super subject', $orElse);
    }

    /**
     * @test
     */
    public function shouldControlMatched_orReturn()
    {
        // given
        $matchGroup = $this->matchGroup();

        // when
        $orReturn = $matchGroup->orReturn(13);

        // then
        $this->assertEquals(13, $orReturn);
    }

    private function matchGroup(): MatchGroup
    {
        return new NotMatchedGroup('first', 1, 'first', 'My super subject', []);
    }
}

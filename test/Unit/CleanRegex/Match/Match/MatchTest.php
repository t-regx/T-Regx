<?php
namespace Unit\CleanRegex\Match\Match;

use CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use CleanRegex\Match\Match;
use PHPUnit\Framework\TestCase;
use SafeRegex\preg;

class MatchTest extends TestCase
{
    const INDEX_TYLER_DURDEN = 0;
    const INDEX_MARLA_SINGER = 1;
    const INDEX_ROBERT_PAULSON = 2;
    const INDEX_JACK_SPARROW = 3;

    const subject = 'people are always asking me if I know Tyler Durden. and suddenly I realize that all of this: ' . PHP_EOL
    . 'the gun, the bombs, the revolution... has got something to do with a girl named Marla Singer. ' . PHP_EOL
    . 'in death a member of project mayhem has a name. his name is Robert P***son.' . PHP_EOL
    . PHP_EOL
    . "when you marooned me on that god forsaken spit of land, you forgot one very important thing mate. i'm captain Jack Sparrow.";

    /**
     * @test
     */
    public function shouldGetSubject()
    {
        // given
        $match = $this->getMatch(self::INDEX_ROBERT_PAULSON);

        // when
        $subject = $match->subject();

        // then
        $this->assertEquals(self::subject, $subject);
    }

    /**
     * @test
     */
    public function shouldGetIndex()
    {
        // given
        $match = $this->getMatch(self::INDEX_ROBERT_PAULSON);

        // when
        $index = $match->index();

        // then
        $this->assertEquals(2, $index);
    }

    /**
     * @test
     */
    public function shouldGetOffset()
    {
        // given
        $match = $this->getMatch(self::INDEX_MARLA_SINGER);

        // when
        $offset = $match->offset();

        // then
        $this->assertEquals(173 + strlen(PHP_EOL), $offset);
    }

    /**
     * @test
     */
    public function shouldGetMatch()
    {
        // given
        $match = $this->getMatch(self::INDEX_JACK_SPARROW);

        // when
        $match = $match->match();

        // then
        $this->assertEquals('Jack Sparrow', $match);
    }

    /**
     * @test
     */
    public function shouldGetMatchCastingToString()
    {
        // given
        $match = $this->getMatch(self::INDEX_JACK_SPARROW);

        // when
        $match = (string)$match;

        // then
        $this->assertEquals('Jack Sparrow', $match);
    }

    /**
     * @test
     */
    public function shouldGetGroups()
    {
        // given
        $match = $this->getMatch(self::INDEX_TYLER_DURDEN);

        // when
        $groups = $match->groups();

        // then
        $this->assertEquals(['Tyler', 'T', 'Durden'], $groups);
    }

    /**
     * @test
     */
    public function shouldGetSingleGroups()
    {
        // given
        $match = $this->getMatch(self::INDEX_MARLA_SINGER);

        // then
        $firstName = $match->group('firstName');
        $initial = $match->group('initial');
        $surname = $match->group('surname');

        // then
        $this->assertEquals('Marla', $firstName);
        $this->assertEquals('M', $initial);
        $this->assertEquals('Singer', $surname);
    }

    /**
     * @test
     */
    public function shouldGetGroupNames()
    {
        // given
        $match = $this->getMatch(self::INDEX_JACK_SPARROW);

        // when
        $names = $match->groupNames();

        // then
        $this->assertEquals(['firstName', 'initial', 'surname'], $names);
    }

    /**
     * @test
     */
    public function shouldGetNamedGroups()
    {
        // given
        $match = $this->getMatch(self::INDEX_JACK_SPARROW);

        // when
        $names = $match->namedGroups();

        // then
        $expected = [
            'firstName' => 'Jack',
            'initial' => 'J',
            'surname' => 'Sparrow'
        ];
        $this->assertEquals($expected, $names);
    }

    /**
     * @test
     */
    public function shouldHaveGroup()
    {
        // given
        $match = $this->getMatch(self::INDEX_TYLER_DURDEN);

        // when
        $existent = $match->hasGroup('firstName');
        $nonExistent = $match->hasGroup('xd');

        // then
        $this->assertTrue($existent);
        $this->assertFalse($nonExistent);
    }

    /**
     * @test
     */
    public function shouldMatchGroup()
    {
        // given
        $match = $this->getMatch(self::INDEX_MARLA_SINGER);

        // when
        $matched = $match->matched('firstName');

        // then
        $this->assertTrue($matched);
    }

    /**
     * @test
     */
    public function shouldNotMatchGroup()
    {
        // given
        $match = $this->getMatch(self::INDEX_ROBERT_PAULSON);

        // when
        $surname = $match->matched('surname');

        // then
        $this->assertFalse($surname);
    }

    /**
     * @test
     */
    public function shouldThrowOnNonExistentGroup()
    {
        // given
        $match = $this->getMatch(self::INDEX_MARLA_SINGER);

        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: xd");

        // when
        $match->matched('xd');
    }

    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $match = $this->getMatch(self::INDEX_JACK_SPARROW);

        // when
        $all = $match->all();

        // then
        $this->assertEquals(['Tyler Durden', 'Marla Singer', 'Robert', 'Jack Sparrow'], $all);
    }

    private function getMatch(int $index): Match
    {
        $pattern = '/(?<firstName>(?<initial>[A-Z])[a-z]+)(?: (?<surname>[A-Z][a-z]+))?/';

        $matches = [];
        preg::match_all($pattern, self::subject, $matches, PREG_OFFSET_CAPTURE);
        return new Match(self::subject, $index, $matches);
    }
}

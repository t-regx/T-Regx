<?php
namespace Test\Integration\TRegx\CleanRegex\Match;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Adapter\RawMatchesToMatchAdapter;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\CleanRegex\Internal\SubjectableImpl;
use TRegx\CleanRegex\Match\Details\Match;
use TRegx\CleanRegex\Match\Details\MatchImpl;
use TRegx\SafeRegex\preg;

class MatchImplTest extends TestCase
{
    const INDEX_TYLER_DURDEN = 0;
    const INDEX_MARLA_SINGER = 1;
    const INDEX_ROBERT_PAULSON = 2;
    const INDEX_JACK_SPARROW = 3;
    const INDEX_EDWARD = 4;

    const subject = 'people are always asking me if I know Tyler Durden. and suddenly I realize that all of this: ' . PHP_EOL
    . 'the gun, the bombs, the revolution... has got something to do with a girl named Marla Singer. ' . PHP_EOL
    . 'in death a member of project mayhem has a name. his name is Robert P***son.' . PHP_EOL
    . PHP_EOL
    . "when you marooned me on that god forsaken spit of land, you forgot one very important thing mate. i'm captain Jack Sparrow." . PHP_EOL
    . "Ędward.";

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
    public function shouldGetGroupsOffset()
    {
        // given
        $match = $this->getMatch(self::INDEX_MARLA_SINGER);

        // when
        $offsets = $match->groups()->offsets();

        // then
        $rn = strlen(PHP_EOL);
        $expectedOffsets = [
            173 + $rn,
            173 + $rn,
            179 + $rn,
        ];
        $this->assertEquals($expectedOffsets, $offsets);
    }

    /**
     * @test
     */
    public function shouldGetNamedGroupsOffset()
    {
        // given
        $match = $this->getMatch(self::INDEX_MARLA_SINGER);

        // when
        $offsets = $match->namedGroups()->offsets();

        // then
        $rn = strlen(PHP_EOL);
        $expectedOffsets = [
            'firstName' => 173 + $rn,
            'initial'   => 173 + $rn,
            'surname'   => 179 + $rn,
        ];
        $this->assertEquals($expectedOffsets, $offsets);
    }

    /**
     * @test
     */
    public function shouldGetMatch()
    {
        // given
        $match = $this->getMatch(self::INDEX_JACK_SPARROW);

        // when
        $text = $match->text();

        // then
        $this->assertEquals('Jack Sparrow', $text);
    }

    /**
     * @test
     */
    public function shouldGetMatchLength()
    {
        // given
        $match = $this->getMatch(self::INDEX_EDWARD);

        // when
        $length = $match->textLength();

        // then
        $this->assertEquals(6, $length);
    }

    /**
     * @test
     */
    public function shouldGetMatchCastingToString()
    {
        // given
        $match = $this->getMatch(self::INDEX_JACK_SPARROW);

        // when
        $text = (string)$match;

        // then
        $this->assertEquals('Jack Sparrow', $text);
    }

    /**
     * @test
     */
    public function shouldGetGroups()
    {
        // given
        $match = $this->getMatch(self::INDEX_TYLER_DURDEN);

        // when
        $groups = $match->groups()->texts();

        // then
        $this->assertEquals(['Tyler', 'T', 'Durden'], $groups);
    }

    /**
     * @test
     */
    public function shouldGetNamedGroups()
    {
        // given
        $match = $this->getMatch(self::INDEX_JACK_SPARROW);

        // when
        $named = $match->namedGroups()->texts();

        // then
        $expected = [
            'firstName' => 'Jack',
            'initial'   => 'J',
            'surname'   => 'Sparrow'
        ];
        $this->assertEquals($expected, $named);
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
        $this->expectExceptionMessage("Nonexistent group: 'xd'");

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
        $this->assertEquals(['Tyler Durden', 'Marla Singer', 'Robert', 'Jack Sparrow', 'Ędward'], $all);
    }

    /**
     * @test
     */
    public function shouldGet_groupAll()
    {
        // given
        $match = $this->getMatch(self::INDEX_JACK_SPARROW);

        // when
        $all = $match->group('surname')->all();

        // then
        $this->assertEquals(['Durden', 'Singer', null, 'Sparrow', null], $all);
    }

    /**
     * @test
     */
    public function shouldThrow_onNonexistentGroup()
    {
        // given
        $match = $this->getMatch(self::INDEX_JACK_SPARROW);

        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        $match->group('missing')->all();
    }

    /**
     * @test
     */
    public function shouldValidateGroupNameType()
    {
        // given
        $match = $this->getMatch(self::INDEX_JACK_SPARROW);

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Group index can only be an integer or a string, given: boolean (true)');

        // when
        $match->group(true);
    }

    /**
     * @test
     */
    public function shouldPreserveUserData()
    {
        // given
        $match = $this->getMatch(self::INDEX_JACK_SPARROW);
        $mixed = new \stdClass();
        $mixed->value = 'foo';

        // when
        $match->setUserData($mixed);
        $userData = $match->getUserData();

        // then
        $expected = new \stdClass();
        $expected->value = 'foo';
        $this->assertEquals($expected, $userData);
    }

    private function getMatch(int $index): Match
    {
        $pattern = '/(?<firstName>(?<initial>\p{Lu})[a-z]+)(?: (?<surname>[A-Z][a-z]+))?/u';
        preg::match_all($pattern, self::subject, $matches, PREG_OFFSET_CAPTURE);

        $rawMatches = new RawMatchesOffset($matches);
        return new MatchImpl(
            new SubjectableImpl(self::subject),
            $index,
            -1,
            new RawMatchesToMatchAdapter($rawMatches, $index),
            new EagerMatchAllFactory($rawMatches),
            new UserData()
        );
    }
}

<?php
namespace Test\Integration\TRegx\CleanRegex\Match\Details;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Adapter\RawMatchesToMatchAdapter;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\DetailImpl;
use TRegx\SafeRegex\preg;

class MatchImplTest extends TestCase
{
    private const INDEX_TYLER_DURDEN = 0;
    private const INDEX_MARLA_SINGER = 1;
    private const INDEX_ROBERT_PAULSON = 2;
    private const INDEX_JACK_SPARROW = 3;
    private const INDEX_EDWARD = 4;

    private const subject = "people are always asking me if I know Tyler Durden. and suddenly I realize that all of this: 
the gun, the bombs, the revolution... has got something to do with a girl named Marla Singer. 
in death a member of project mayhem has a name. his name is Robert P***son.

when you marooned me on that god forsaken spit of land, you forgot one very important thing mate. i'm captain Jack Sparrow.
Ędward.";

    /**
     * @test
     */
    public function shouldGetSubject()
    {
        // given
        $detail = $this->detail(self::INDEX_ROBERT_PAULSON);

        // when
        $subject = $detail->subject();

        // then
        $this->assertEquals(self::subject, $subject);
    }

    /**
     * @test
     */
    public function shouldGetIndex()
    {
        // given
        $detail = $this->detail(self::INDEX_ROBERT_PAULSON);

        // when
        $index = $detail->index();

        // then
        $this->assertEquals(2, $index);
    }

    /**
     * @test
     */
    public function shouldGetOffset()
    {
        // given
        $detail = $this->detail(self::INDEX_MARLA_SINGER);

        // when
        $offset = $detail->offset();

        // then
        $this->assertEquals(174, $offset);
    }

    /**
     * @test
     */
    public function shouldGetGroupsOffset()
    {
        // given
        $detail = $this->detail(self::INDEX_MARLA_SINGER);

        // when
        $offsets = $detail->groups()->offsets();

        // then
        $this->assertEquals([174, 174, 180], $offsets);
    }

    /**
     * @test
     */
    public function shouldGetNamedGroupsOffset()
    {
        // given
        $detail = $this->detail(self::INDEX_MARLA_SINGER);

        // when
        $offsets = $detail->namedGroups()->offsets();

        // then
        $expectedOffsets = [
            'firstName' => 174,
            'initial'   => 174,
            'surname'   => 180,
        ];
        $this->assertEquals($expectedOffsets, $offsets);
    }

    /**
     * @test
     */
    public function shouldGetMatch()
    {
        // given
        $detail = $this->detail(self::INDEX_JACK_SPARROW);

        // when
        $text = $detail->text();

        // then
        $this->assertEquals('Jack Sparrow', $text);
    }

    /**
     * @test
     */
    public function shouldGetMatchLength()
    {
        // given
        $detail = $this->detail(self::INDEX_EDWARD);

        // when
        $length = $detail->textLength();

        // then
        $this->assertEquals(6, $length);
    }

    /**
     * @test
     */
    public function shouldGetMatchCastingToString()
    {
        // given
        $detail = $this->detail(self::INDEX_JACK_SPARROW);

        // when
        $text = (string)$detail;

        // then
        $this->assertEquals('Jack Sparrow', $text);
    }

    /**
     * @test
     */
    public function shouldGetGroups()
    {
        // given
        $detail = $this->detail(self::INDEX_TYLER_DURDEN);

        // when
        $groups = $detail->groups()->texts();

        // then
        $this->assertEquals(['Tyler', 'T', 'Durden'], $groups);
    }

    /**
     * @test
     */
    public function shouldGetNamedGroups()
    {
        // given
        $detail = $this->detail(self::INDEX_JACK_SPARROW);

        // when
        $named = $detail->namedGroups()->texts();

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
        $detail = $this->detail(self::INDEX_MARLA_SINGER);

        // then
        $firstName = $detail->group('firstName');
        $initial = $detail->group('initial');
        $surname = $detail->group('surname');

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
        $detail = $this->detail(self::INDEX_JACK_SPARROW);

        // when
        $names = $detail->groupNames();

        // then
        $this->assertEquals(['firstName', 'initial', 'surname'], $names);
    }

    /**
     * @test
     */
    public function shouldHaveGroup()
    {
        // given
        $detail = $this->detail(self::INDEX_TYLER_DURDEN);

        // when
        $existent = $detail->hasGroup('firstName');
        $nonExistent = $detail->hasGroup('xd');

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
        $detail = $this->detail(self::INDEX_MARLA_SINGER);

        // when
        $matched = $detail->matched('firstName');

        // then
        $this->assertTrue($matched);
    }

    /**
     * @test
     */
    public function shouldNotMatchGroup()
    {
        // given
        $detail = $this->detail(self::INDEX_ROBERT_PAULSON);

        // when
        $surname = $detail->matched('surname');

        // then
        $this->assertFalse($surname);
    }

    /**
     * @test
     */
    public function shouldThrowOnNonExistentGroup()
    {
        // given
        $detail = $this->detail(self::INDEX_MARLA_SINGER);

        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'xd'");

        // when
        $detail->matched('xd');
    }

    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $detail = $this->detail(self::INDEX_JACK_SPARROW);

        // when
        $all = $detail->all();

        // then
        $this->assertEquals(['Tyler Durden', 'Marla Singer', 'Robert', 'Jack Sparrow', 'Ędward'], $all);
    }

    /**
     * @test
     */
    public function shouldGet_groupAll()
    {
        // given
        $detail = $this->detail(self::INDEX_JACK_SPARROW);

        // when
        $all = $detail->group('surname')->all();

        // then
        $this->assertEquals(['Durden', 'Singer', null, 'Sparrow', null], $all);
    }

    /**
     * @test
     */
    public function shouldThrow_onNonexistentGroup()
    {
        // given
        $detail = $this->detail(self::INDEX_JACK_SPARROW);

        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        $detail->group('missing')->all();
    }

    /**
     * @test
     */
    public function shouldValidateGroupNameType()
    {
        // given
        $detail = $this->detail(self::INDEX_JACK_SPARROW);

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Group index can only be an integer or a string, given: boolean (true)');

        // when
        $detail->group(true);
    }

    /**
     * @test
     */
    public function shouldPreserveUserData()
    {
        // given
        $detail = $this->detail(self::INDEX_JACK_SPARROW);
        $mixed = new \stdClass();
        $mixed->value = 'foo';

        // when
        $detail->setUserData($mixed);
        $userData = $detail->getUserData();

        // then
        $expected = new \stdClass();
        $expected->value = 'foo';
        $this->assertEquals($expected, $userData);
    }

    private function detail(int $index): Detail
    {
        /**
         * We could hardcore the matches here, instead of calculating it, but this way,
         * if there's a compatibility break in `preg_match_all()` between versions,
         * we'll know about it.
         * Secondly, now nobody can mess the hardcoded values up.
         */
        $pattern = '/(?<firstName>(?<initial>\p{Lu})[a-z]+)(?: (?<surname>[A-Z][a-z]+))?/u';
        preg::match_all($pattern, self::subject, $matches, \PREG_OFFSET_CAPTURE);

        $rawMatches = new RawMatchesOffset($matches);
        return new DetailImpl(
            new Subject(self::subject),
            $index,
            -1,
            new RawMatchesToMatchAdapter($rawMatches, $index),
            new EagerMatchAllFactory($rawMatches),
            new UserData()
        );
    }
}

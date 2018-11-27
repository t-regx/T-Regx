<?php
namespace Test\UnitCleanRegex\Match;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Model\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\RawMatchOffset;
use TRegx\CleanRegex\Internal\SubjectableEx;
use TRegx\CleanRegex\Internal\SubjectableImpl;
use TRegx\CleanRegex\Match\Details\ReplaceMatch;
use TRegx\SafeRegex\preg;

class ReplaceMatchTest extends TestCase
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
    public function shouldSliceAllToLimit()
    {
        // given
        $match = $this->getMatch(0, 0, 2);

        // when
        $all = $match->all();

        // then
        $this->assertEquals(['Tyler Durden', 'Marla Singer'], $all);
    }

    /**
     * @test
     */
    public function shouldNotSliceAllForNegativeLimit()
    {
        // given
        $match = $this->getMatch(0, 0, -1);

        // when
        $all = $match->all();

        // then
        $this->assertEquals(['Tyler Durden', 'Marla Singer', 'Robert', 'Jack Sparrow'], $all);
    }

    /**
     * @test
     */
    public function shouldGetUnlimitedRegardlessOfLimit()
    {
        // given
        $match = $this->getMatch(0, 0, 2);

        // when
        $all = $match->allUnlimited();

        // then
        $this->assertEquals(['Tyler Durden', 'Marla Singer', 'Robert', 'Jack Sparrow'], $all);
    }

    /**
     * @test
     */
    public function shouldModifyOffset()
    {
        // given
        $match = $this->getMatch(0, 15, 0);

        // when
        $offset = $match->offset();
        $modifiedOffset = $match->modifiedOffset();

        // then
        $this->assertEquals(38, $offset);
        $this->assertEquals(53, $modifiedOffset);
    }

    private function getMatch(int $index, int $offsetModification, int $limit): ReplaceMatch
    {
        $pattern = '/(?<firstName>(?<initial>[A-Z])[a-z]+)(?: (?<surname>[A-Z][a-z]+))?/';
        preg::match($pattern, self::subject, $match, PREG_OFFSET_CAPTURE);
        preg::match_all($pattern, self::subject, $matches, PREG_OFFSET_CAPTURE);

        return new ReplaceMatch(
            new SubjectableImpl(self::subject),
            $index,
            new RawMatchOffset($match),
            new EagerMatchAllFactory(new RawMatchesOffset($matches, new SubjectableEx())),
            $offsetModification,
            '',
            $limit);
    }
}

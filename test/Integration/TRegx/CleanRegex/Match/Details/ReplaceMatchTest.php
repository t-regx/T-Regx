<?php
namespace Test\Integration\CleanRegex\Match;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Match\Details\Group\ReplaceMatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Adapter\RawMatchesToMatchAdapter;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\CleanRegex\Internal\SubjectableEx;
use TRegx\CleanRegex\Internal\SubjectableImpl;
use TRegx\CleanRegex\Match\Details\MatchImpl;
use TRegx\CleanRegex\Match\Details\ReplaceMatch;
use TRegx\CleanRegex\Match\Details\ReplaceMatchImpl;
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
        $match = $this->getMatch(0);

        // when
        $all = $match->all();

        // then
        $this->assertEquals(['Tyler Durden', 'Marla Singer', 'Robert', 'Jack Sparrow'], $all);
    }

    /**
     * @test
     */
    public function shouldNotSliceAllForNegativeLimit()
    {
        // given
        $match = $this->getMatch(0);

        // when
        $all = $match->all();

        // then
        $this->assertEquals(['Tyler Durden', 'Marla Singer', 'Robert', 'Jack Sparrow'], $all);
    }

    /**
     * @test
     */
    public function shouldModifyOffset()
    {
        // given
        $match = $this->getMatch(15);

        // when
        $offset = $match->offset();
        $modifiedOffset = $match->modifiedOffset();

        // then
        $this->assertEquals(38, $offset);
        $this->assertEquals(53, $modifiedOffset);
    }

    private function getMatch(int $offsetModification): ReplaceMatch
    {
        $pattern = '/(?<firstName>(?<initial>[A-Z])[a-z]+)(?: (?<surname>[A-Z][a-z]+))?/';
        preg::match_all($pattern, self::subject, $matches, PREG_OFFSET_CAPTURE);

        $matches = new RawMatchesOffset($matches);
        return new ReplaceMatchImpl(
            new MatchImpl(
                new SubjectableImpl(self::subject),
                0,
                -1,
                new RawMatchesToMatchAdapter($matches, 0),
                new EagerMatchAllFactory($matches),
                new UserData(),
                new ReplaceMatchGroupFactoryStrategy($offsetModification)
            ),
            $offsetModification,
            '');
    }
}

<?php
namespace Test\Integration\TRegx\CleanRegex\Match\Details;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Match\Details\Group\ReplaceMatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Adapter\RawMatchesToMatchAdapter;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\DetailImpl;
use TRegx\CleanRegex\Match\Details\ReplaceDetail;
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
        $detail = $this->detail(0);

        // when
        $all = $detail->all();

        // then
        $this->assertEquals(['Tyler Durden', 'Marla Singer', 'Robert', 'Jack Sparrow'], $all);
    }

    /**
     * @test
     */
    public function shouldNotSliceAllForNegativeLimit()
    {
        // given
        $detail = $this->detail(0);

        // when
        $all = $detail->all();

        // then
        $this->assertEquals(['Tyler Durden', 'Marla Singer', 'Robert', 'Jack Sparrow'], $all);
    }

    /**
     * @test
     */
    public function shouldModifyOffset()
    {
        // given
        $detail = $this->detail(15);

        // when
        $offset = $detail->offset();
        $modifiedOffset = $detail->modifiedOffset();

        // then
        $this->assertEquals(38, $offset);
        $this->assertEquals(53, $modifiedOffset);
    }

    private function detail(int $offsetModification): ReplaceDetail
    {
        /**
         * We could hardcore the matches here, instead of calculating it, but this way,
         * if there's a compatibility break in `preg_match_all()` between versions,
         * we'll know about it.
         * Secondly, now nobody can mess the hardcoded values up.
         */
        $pattern = '/(?<firstName>(?<initial>[A-Z])[a-z]+)(?: (?<surname>[A-Z][a-z]+))?/';
        preg::match_all($pattern, self::subject, $matches, \PREG_OFFSET_CAPTURE);

        $matches = new RawMatchesOffset($matches);
        return new ReplaceMatchImpl(
            new DetailImpl(
                new Subject(self::subject),
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

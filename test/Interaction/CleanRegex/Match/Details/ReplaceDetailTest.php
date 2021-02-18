<?php
namespace Test\Interaction\TRegx\CleanRegex\Match\Details;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Match\Details\Group\ReplaceMatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Adapter\RawMatchesToMatchAdapter;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\MatchDetail;
use TRegx\CleanRegex\Match\Details\ReplaceDetail;
use TRegx\CleanRegex\Match\Details\ReplaceDetailImpl;
use TRegx\SafeRegex\preg;

class ReplaceDetailTest extends TestCase
{
    private const subject = "people are always asking me if I know Tyler Durden. and suddenly I realize that all of this: 
the gun, the bombs, the revolution... has got something to do with a girl named Marla Singer. 
in death a member of project mayhem has a name. his name is Robert P***son.

when you marooned me on that god forsaken spit of land, you forgot one very important thing mate. i'm captain Jack Sparrow.";

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
        $this->assertSame(['Tyler Durden', 'Marla Singer', 'Robert', 'Jack Sparrow'], $all);
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
        $this->assertSame(['Tyler Durden', 'Marla Singer', 'Robert', 'Jack Sparrow'], $all);
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
        $modifiedOffset = $detail->byteModifiedOffset();

        // then
        $this->assertSame(38, $offset);
        $this->assertSame(53, $modifiedOffset);
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
        return new ReplaceDetailImpl(
            new MatchDetail(
                new Subject(self::subject),
                0,
                -1,
                new RawMatchesToMatchAdapter($matches, 0),
                new EagerMatchAllFactory($matches),
                new UserData(),
                new ReplaceMatchGroupFactoryStrategy(-1, '')
            ),
            $offsetModification,
            '');
    }
}

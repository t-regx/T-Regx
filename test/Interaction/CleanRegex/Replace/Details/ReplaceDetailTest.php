<?php
namespace Test\Interaction\TRegx\CleanRegex\Replace\Details;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Match\Details\DeprecatedMatchDetail;
use TRegx\CleanRegex\Internal\Match\Details\Group\ReplaceMatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\RawMatchesToMatchAdapter;
use TRegx\CleanRegex\Internal\Replace\Details\Modification;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Replace\Details\ReplaceDetail;

/**
 * @covers \TRegx\CleanRegex\Replace\Details\ReplaceDetail
 */
class ReplaceDetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGet_all()
    {
        // given
        $matches = [[['Tyler Durden', 0], ['Marla Singer', 1]],];
        $detail = $this->detail('', $matches, -1);

        // when
        $all = $detail->all();

        // then
        $this->assertSame(['Tyler Durden', 'Marla Singer'], $all);
    }

    /**
     * @test
     */
    public function shouldGet_modifiedOffset()
    {
        // given
        $detail = $this->detail('€€ Bar', [[['', 3]]], 3);

        // when
        $offset = $detail->offset();
        $modifiedOffset = $detail->modifiedOffset();

        // then
        $this->assertSame(1, $offset);
        $this->assertSame(2, $modifiedOffset);
    }

    /**
     * @test
     */
    public function shouldGet_byteModifiedOffset()
    {
        // given
        $detail = $this->detail('€€ Bar', [[['', 3]]], 3);

        // when
        $offset = $detail->byteOffset();
        $modifiedOffset = $detail->byteModifiedOffset();

        // then
        $this->assertSame(3, $offset);
        $this->assertSame(6, $modifiedOffset);
    }

    private function detail(string $subject, array $matches, int $offsetModification): ReplaceDetail
    {
        $matches = new RawMatchesOffset($matches);
        $match = new RawMatchesToMatchAdapter($matches, 0);
        return new ReplaceDetail(DeprecatedMatchDetail::create(
            new Subject($subject),
            0,
            -1,
            $match,
            new EagerMatchAllFactory($matches),
            new UserData(),
            new ReplaceMatchGroupFactoryStrategy(-1, '')),
            new Modification($match, $subject, $offsetModification));
    }
}

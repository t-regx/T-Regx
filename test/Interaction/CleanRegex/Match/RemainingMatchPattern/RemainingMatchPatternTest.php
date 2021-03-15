<?php
namespace Test\Interaction\TRegx\CleanRegex\Match\RemainingMatchPattern;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsSameMatches;
use Test\Utils\CallbackPredicate;
use Test\Utils\Functions;
use Test\Utils\Internal;
use Test\Utils\ThrowApiBase;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\DetailPredicateBaseDecorator;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Match\AbstractMatchPattern;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\RemainingMatchPattern;

class RemainingMatchPatternTest extends TestCase
{
    use AssertsSameMatches;

    /**
     * @test
     */
    public function shouldAll()
    {
        // given
        $matchPattern = $this->standardMatchPattern_secondFiltered();

        // when
        $all = $matchPattern->all();

        // then
        $this->assertSame(['first', 'third', 'fourth'], $all);
    }

    /**
     * @test
     */
    public function shouldOnly_2()
    {
        // given
        $matchPattern = $this->standardMatchPattern_secondFiltered();

        // when
        $only = $matchPattern->only(2);

        // then
        $this->assertSame(['first', 'third'], $only);
    }

    /**
     * @test
     */
    public function shouldOnly_1()
    {
        // given
        $matchPattern = $this->standardMatchPattern_secondFiltered();

        // when
        $only = $matchPattern->only(1);

        // then
        $this->assertSame(['first'], $only);
    }

    /**
     * @test
     */
    public function shouldOnly_0()
    {
        // given
        $matchPattern = $this->standardMatchPattern_secondFiltered();

        // when
        $only = $matchPattern->only(0);

        // then
        $this->assertEmpty($only);
    }

    /**
     * @test
     */
    public function shouldGet_getIterator()
    {
        // given
        $matchPattern = $this->matchPattern('\w+', 'a b c b e', Functions::notEquals('b'));

        // when
        $iterator = $matchPattern->getIterator();

        // then
        $this->assertSameMatches(['a', 'c', 'e'], iterator_to_array($iterator));
    }

    /**
     * @test
     */
    public function shouldFlatMap()
    {
        // given
        $matchPattern = $this->standardMatchPattern_firstFiltered();
        $callback = function (Detail $detail) {
            return str_split($detail, 3);
        };

        // when
        $flatMap = $matchPattern->flatMap($callback);

        // then
        $this->assertSame(['sec', 'ond', 'thi', 'rd', 'fou', 'rth'], $flatMap);
    }

    /**
     * @test
     */
    public function shouldFirst_firstFiltered()
    {
        // given
        $matchPattern = $this->matchPattern('[a-z]+', 'first second', Functions::notEquals('first'));

        // when
        $first = $matchPattern->first();

        // then
        $this->assertSame('second', $first);
    }

    /**
     * @test
     */
    public function shouldFirst_secondFiltered()
    {
        // given
        $matchPattern = $this->standardMatchPattern_secondFiltered();

        // when
        $first = $matchPattern->first();

        // then
        $this->assertSame('first', $first);
    }

    /**
     * @test
     */
    public function shouldOffsets()
    {
        // given
        $matchPattern = $this->standardMatchPattern_secondFiltered();

        // when
        $offsets = $matchPattern->offsets()->all();

        // then
        $this->assertSame([0, 13, 19], $offsets);
    }

    /**
     * @test
     */
    public function shouldGroup_all()
    {
        // given
        $matchPattern = $this->standardMatchPattern_group('First Second Third Fourth', 'Second');

        // when
        $all = $matchPattern->group('capital')->all();

        // then
        $this->assertSame(['F', 'T', 'F'], $all);
    }

    /**
     * @test
     */
    public function shouldGroup_only()
    {
        // given
        $matchPattern = $this->standardMatchPattern_group('First Second Third Fourth', 'Second');

        // when
        $all = $matchPattern->group('capital')->only(2);

        // then
        $this->assertSame(['F', 'T'], $all);
    }

    /**
     * @test
     */
    public function shouldGroup_first()
    {
        // given
        $matchPattern = $this->standardMatchPattern_group('First Second Third', 'First');

        // when
        $firstGroup = $matchPattern->group('capital')->first();

        // then
        $this->assertSame('S', $firstGroup);
    }

    /**
     * @test
     */
    public function shouldThrow_remaining_forInvalidReturnType(): void
    {
        // given
        $pattern = $this->matchPattern('Foo', 'Foo', Functions::constant(true));

        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid remaining() callback return type. Expected bool, but integer (2) given');

        // when
        $pattern->remaining(Functions::constant(2))->first();
    }

    /**
     * @test
     */
    public function shouldMap()
    {
        // given
        $matchPattern = $this->standardMatchPattern_secondFiltered();
        $mapper = function (Detail $detail) {
            return lcfirst(strtoupper($detail));
        };

        // when
        $mapped = $matchPattern->map($mapper);

        // then
        $this->assertSame(['fIRST', 'tHIRD', 'fOURTH'], $mapped);
    }

    /**
     * @test
     */
    public function shouldFindFirst_firstFiltered()
    {
        // given
        $matchPattern = $this->standardMatchPattern_firstFiltered();
        $callback = function (Detail $detail) {
            return "for first: $detail";
        };

        // when
        $findFirst = $matchPattern->findFirst($callback)->orReturn('');

        // then
        $this->assertSame('for first: second', $findFirst);
    }

    /**
     * @test
     */
    public function shouldForFirst_secondFiltered()
    {
        // given
        $matchPattern = $this->standardMatchPattern_secondFiltered();
        $callback = function (Detail $detail) {
            return "for first: $detail";
        };

        // when
        $findFirst = $matchPattern->findFirst($callback)->orReturn('');

        // then
        $this->assertSame('for first: first', $findFirst);
    }

    /**
     * @test
     */
    public function shouldChain_remaining()
    {
        // given
        $pattern = '[a-z]+';
        $subject = '...you forgot one very important thing mate.';

        // when
        $filtered = $this
            ->matchPattern($pattern, $subject, Functions::notEquals('forgot'))
            ->remaining(Functions::notEquals('very'))
            ->remaining(Functions::notEquals('mate'))
            ->all();

        // then
        $this->assertSame(['you', 'one', 'important', 'thing'], $filtered);
    }

    /**
     * @test
     */
    public function shouldChain_remaining_preserveIndex()
    {
        // given
        $pattern = '[a-z]+';
        $subject = '...you forgot one very important thing mate.';

        // when
        $indexes = $this
            ->matchPattern($pattern, $subject, Functions::notEquals('forgot'))
            ->remaining(Functions::notEquals('very'))
            ->remaining(Functions::notEquals('thing'))
            ->flatMap(function (Detail $detail) {
                return ["$detail" => $detail->index()];
            });

        // then
        $expected = [
            'you'       => 0,
            'one'       => 2,
            'important' => 4,
            'mate'      => 6
        ];
        $this->assertSame($expected, $indexes);
    }

    /**
     * @test
     */
    public function shouldDetailAll_returnAll()
    {
        // given
        $pattern = '[a-z]+';
        $subject = '...you forgot one very important thing mate.';

        // when
        $filtered = $this
            ->matchPattern($pattern, $subject, Functions::notEquals('forgot'))
            ->remaining(Functions::notEquals('very'))
            ->remaining(Functions::notEquals('mate'))
            ->first(function (Detail $detail) {
                return $detail->all();
            });

        // then
        $this->assertSame(['you', 'forgot', 'one', 'very', 'important', 'thing', 'mate'], $filtered);
    }

    /**
     * @test
     */
    public function shouldFilter_first_untilFound()
    {
        // given
        $invokedFor = [];
        $pattern = $this->matchPattern('(one|two|three|four|five|six)', 'one two three four five six', function (Detail $detail) use (&$invokedFor) {
            $invokedFor[] = $detail->text();
            return $detail->text() === 'four';
        });

        // when
        $first = $pattern->first();

        // then
        $this->assertSame('four', $first);
        $this->assertSame(['one', 'two', 'three', 'four'], $invokedFor);
    }

    /**
     * @test
     */
    public function shouldFilter_all_untilFound()
    {
        // given
        $invokedFor = [];
        $pattern = $this->matchPattern('(one|two|three|four|five|six)', 'one two three four five six', function (Detail $detail) use (&$invokedFor) {
            $invokedFor[] = $detail->text();
            return $detail->text() === 'four';
        });

        // when
        $all = $pattern->all();

        // then
        $this->assertSame(['four'], $all);
        $this->assertSame(['one', 'two', 'three', 'four', 'five', 'six'], $invokedFor);
    }

    /**
     * @test
     */
    public function shouldForEach()
    {
        // given
        $matchPattern = $this->standardMatchPattern_secondFiltered();
        $matches = [];
        $callback = function (Detail $detail) use (&$matches) {
            $matches[] = $detail->text();
        };

        // when
        $matchPattern->forEach($callback);

        // then
        $this->assertSame(['first', 'third', 'fourth'], $matches);
    }

    /**
     * @test
     */
    public function shouldGet_iterator()
    {
        // given
        $matchPattern = $this->standardMatchPattern_secondFiltered();

        // when
        $iterator = $matchPattern->getIterator();

        // then
        $array = [];
        foreach ($iterator as $match) {
            $array[] = $match->text();
        }
        $this->assertSame(['first', 'third', 'fourth'], $array);
    }

    private function standardMatchPattern_secondFiltered(): AbstractMatchPattern
    {
        return $this->matchPattern('[a-z]+', 'first second third fourth', Functions::notEquals('second'));
    }

    private function standardMatchPattern_firstFiltered(): AbstractMatchPattern
    {
        return $this->matchPattern('[a-z]+', 'first second third fourth', Functions::notEquals('first'));
    }

    private function standardMatchPattern_group(string $subject, string $filteredOut): AbstractMatchPattern
    {
        return $this->matchPattern('(?<capital>[A-Z])[a-z]+', $subject, Functions::notEquals($filteredOut));
    }

    private function matchPattern(string $pattern, string $subject, callable $predicate): AbstractMatchPattern
    {
        return new RemainingMatchPattern(
            new DetailPredicateBaseDecorator(
                new ApiBase(Internal::pattern($pattern), $subject, new UserData()),
                new CallbackPredicate($predicate)),
            new ThrowApiBase());
    }
}

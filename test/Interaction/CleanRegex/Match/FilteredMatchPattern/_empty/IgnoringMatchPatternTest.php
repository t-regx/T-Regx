<?php
namespace Test\Interaction\TRegx\CleanRegex\Match\FilteredMatchPattern\_empty;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\IgnoreBaseDecorator;
use TRegx\CleanRegex\Internal\Match\FindFirst\EmptyOptional;
use TRegx\CleanRegex\Internal\Match\FindFirst\OptionalImpl;
use TRegx\CleanRegex\Internal\Match\Predicate;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\IgnoringMatchPattern;

class IgnoringMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGet_All()
    {
        // given
        $matchPattern = $this->standardMatchPattern();

        // when
        $all = $matchPattern->all();

        // then
        $this->assertSame(['', 'a', '', 'c'], $all);
    }

    /**
     * @test
     */
    public function shouldOnly_2()
    {
        // given
        $matchPattern = $this->standardMatchPattern();

        // when
        $only = $matchPattern->only(2);

        // then
        $this->assertSame(['', 'a'], $only);
    }

    /**
     * @test
     */
    public function shouldOnly_1()
    {
        // given
        $matchPattern = $this->standardMatchPattern();

        // when
        $only = $matchPattern->only(1);

        // then
        $this->assertSame([''], $only);
    }

    /**
     * @test
     */
    public function shouldCount()
    {
        // given
        $matchPattern = $this->standardMatchPattern();

        // when
        $count = $matchPattern->count();

        // then
        $this->assertSame(4, $count);
    }

    /**
     * @test
     */
    public function shouldCount_all()
    {
        // given
        $matchPattern = $this->standardMatchPattern_all();

        // when
        $count = $matchPattern->count();

        // then
        $this->assertSame(5, $count);
    }

    /**
     * @test
     */
    public function shouldGet_First()
    {
        // given
        $matchPattern = $this->standardMatchPattern();

        // when
        $first = $matchPattern->first();

        // then
        $this->assertSame('', $first);
    }

    /**
     * @test
     */
    public function shouldGet_First_notFirst()
    {
        // given
        $matchPattern = $this->standardMatchPattern_notFirst();

        // when
        $first = $matchPattern->first();

        // then
        $this->assertSame('a', $first);
    }

    /**
     * @test
     */
    public function shouldNotGet_First_matchedButFiltered()
    {
        // given
        $matchPattern = $this->standardMatchPattern_filtered();

        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');

        // when
        $matchPattern->first();
    }

    /**
     * @test
     */
    public function shouldGet_findFirst()
    {
        // given
        $matchPattern = $this->standardMatchPattern();

        // when
        $findFirst = $matchPattern->findFirst(function (Detail $detail) {
            return "value: $detail";
        });

        // then
        $this->assertSame('value: ', $findFirst->orThrow());
        $this->assertInstanceOf(OptionalImpl::class, $findFirst);
    }

    /**
     * @test
     */
    public function shouldGet_findFirst_notFirst()
    {
        // given
        $matchPattern = $this->standardMatchPattern_notFirst();

        // when
        $findFirst = $matchPattern->findFirst(function (Detail $detail) {
            return "value: $detail";
        });

        // then
        $this->assertSame('value: a', $findFirst->orThrow());
        $this->assertInstanceOf(OptionalImpl::class, $findFirst);
    }

    /**
     * @test
     */
    public function shouldNotGet_findFirst_matchedButFiltered()
    {
        // given
        $matchPattern = $this->standardMatchPattern_filtered();

        // when
        $findFirst = $matchPattern->findFirst(function (Detail $detail) {
            return "value: $detail";
        });

        // then
        $this->assertInstanceOf(EmptyOptional::class, $findFirst);
    }

    /**
     * @test
     */
    public function shouldMatch_all()
    {
        // given
        $matchPattern = $this->standardMatchPattern_all();

        // when
        $matches = $matchPattern->test();
        $fails = $matchPattern->fails();

        // then
        $this->assertTrue($matches);
        $this->assertFalse($fails);
    }

    /**
     * @test
     */
    public function shouldMatch_some()
    {
        // given
        $matchPattern = $this->standardMatchPattern();

        // when
        $matches = $matchPattern->test();
        $fails = $matchPattern->fails();

        // then
        $this->assertTrue($matches);
        $this->assertFalse($fails);
    }

    /**
     * @test
     */
    public function shouldNotMatch_matchedButFiltered()
    {
        // given
        $matchPattern = $this->standardMatchPattern_filtered();

        // when
        $matches = $matchPattern->test();
        $fails = $matchPattern->fails();

        // then
        $this->assertFalse($matches);
        $this->assertTrue($fails);
    }

    /**
     * @test
     */
    public function shouldFlatMap()
    {
        // given
        $matchPattern = $this->standardMatchPattern_notFirst();

        // when
        $flatMap = $matchPattern->flatMap(function (Detail $detail) {
            return str_split(str_repeat($detail, 2));
        });

        // then
        $this->assertSame(['a', 'a', 'b', 'b', '', 'c', 'c'], $flatMap);
    }

    /**
     * @test
     */
    public function shouldGet_Offsets_all()
    {
        // given
        $matchPattern = $this->standardMatchPattern();

        // when
        $offsets = $matchPattern->offsets()->all();

        // then
        $this->assertSame([1, 4, 12, 15], $offsets);
    }

    /**
     * @test
     */
    public function shouldGet_Offsets_first()
    {
        // given
        $matchPattern = $this->standardMatchPattern();

        // when
        $offset = $matchPattern->offsets()->first();

        // then
        $this->assertSame(1, $offset);
    }

    /**
     * @test
     */
    public function shouldMap()
    {
        // given
        $matchPattern = $this->standardMatchPattern();
        $mapper = function (Detail $detail) {
            return lcfirst($detail) . ucfirst($detail);
        };

        // when
        $mapped = $matchPattern->map($mapper);

        // then
        $this->assertSame(['', 'aA', '', 'cC'], $mapped);
    }

    /**
     * @test
     */
    public function shouldFindFirst()
    {
        // given
        $matchPattern = $this->standardMatchPattern();

        // when
        $first = $matchPattern
            ->findFirst(function (Detail $detail) {
                return "for first: $detail";
            })
            ->orReturn('');

        // then
        $this->assertSame('for first: ', $first);
    }

    /**
     * @test
     */
    public function shouldChain_filter()
    {
        // given
        $subject = '...you forgot one very important thing mate.';
        $predicate = function (Detail $detail) {
            return $detail->text() != 'forgot';
        };
        $pattern = new IgnoringMatchPattern(new IgnoreBaseDecorator(new ApiBase(InternalPattern::pcre('/[a-z]+/'), $subject, new UserData()), new Predicate($predicate)));

        // when
        $filtered = $pattern
            ->ignoring(function (Detail $detail) {
                return $detail->text() !== 'very';
            })
            ->ignoring(function (Detail $detail) {
                return $detail->text() !== 'mate';
            })
            ->all();

        // then
        $this->assertSame(['you', 'one', 'important', 'thing'], $filtered);
    }

    /**
     * @test
     */
    public function shouldForEach()
    {
        // given
        $matchPattern = $this->standardMatchPattern();
        $matches = [];

        // when
        $matchPattern->forEach(function (Detail $detail) use (&$matches) {
            $matches[] = $detail->text();
        });

        // then
        $this->assertSame(['', 'a', '', 'c'], $matches);
    }

    /**
     * @test
     */
    public function shouldGet_iterator()
    {
        // given
        $matchPattern = $this->standardMatchPattern();

        // when
        $iterator = $matchPattern->getIterator();

        // then
        $array = [];
        foreach ($iterator as $detail) {
            $array[] = $detail->text();
        }
        $this->assertSame(['', 'a', '', 'c'], $array);
    }

    private function standardMatchPattern(): IgnoringMatchPattern
    {
        return $this->matchPattern(function (Detail $detail) {
            return $detail->text() !== 'b';
        });
    }

    private function standardMatchPattern_all(): IgnoringMatchPattern
    {
        return $this->matchPattern(Functions::constant(true));
    }

    private function standardMatchPattern_notFirst(): IgnoringMatchPattern
    {
        return $this->matchPattern(function (Detail $detail) {
            return $detail->index() > 0;
        });
    }

    private function standardMatchPattern_filtered(): IgnoringMatchPattern
    {
        return $this->matchPattern(Functions::constant(false));
    }

    private function matchPattern(callable $predicate): IgnoringMatchPattern
    {
        return new IgnoringMatchPattern(new IgnoreBaseDecorator(
            new ApiBase(
                InternalPattern::standard('(?<=\()[a-z]?(?=\))'),
                '() (a) (b) () (c)',
                new UserData()
            ),
            new Predicate($predicate)
        ));
    }
}

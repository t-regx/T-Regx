<?php
namespace Test\Integration\TRegx\CleanRegex\Match\FilteredMatchPattern\_empty;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\FilteredBaseDecorator;
use TRegx\CleanRegex\Internal\Match\Predicate;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Match\AbstractMatchPattern;
use TRegx\CleanRegex\Match\Details\Match;
use TRegx\CleanRegex\Match\FilteredMatchPattern;
use TRegx\CleanRegex\Match\ForFirst\MatchedOptional;
use TRegx\CleanRegex\Match\ForFirst\NotMatchedOptional;

class FilteredMatchPatternTest extends TestCase
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
        $this->assertEquals(['', 'a', '', 'c'], $all);
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
        $this->assertEquals(['', 'a'], $only);
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
        $this->assertEquals([''], $only);
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
        $this->assertEquals(4, $count);
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
        $this->assertEquals(5, $count);
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
        $this->assertEquals($first, '');
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
        $this->assertEquals('a', $first);
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
        $this->expectExceptionMessage('Expected to get first match, but subject was not matched');

        // when
        $matchPattern->first();
    }

    /**
     * @test
     */
    public function shouldGet_ForFirst()
    {
        // given
        $matchPattern = $this->standardMatchPattern();
        $callback = function (Match $match) {
            return 'value: ' . $match->text();
        };

        // when
        $forFirst = $matchPattern->forFirst($callback);

        // then
        $this->assertEquals('value: ', $forFirst->orThrow());
        $this->assertInstanceOf(MatchedOptional::class, $forFirst);
    }

    /**
     * @test
     */
    public function shouldGet_ForFirst_notFirst()
    {
        // given
        $matchPattern = $this->standardMatchPattern_notFirst();
        $callback = function (Match $match) {
            return 'value: ' . $match->text();
        };

        // when
        $forFirst = $matchPattern->forFirst($callback);

        // then
        $this->assertEquals('value: a', $forFirst->orThrow());
        $this->assertInstanceOf(MatchedOptional::class, $forFirst);
    }

    /**
     * @test
     */
    public function shouldNotGet_ForFirst_matchedButFiltered()
    {
        // given
        $matchPattern = $this->standardMatchPattern_filtered();
        $callback = function (Match $match) {
            return 'value: ' . $match->text();
        };

        // then
        $forFirst = $matchPattern->forFirst($callback);

        // then
        $this->assertInstanceOf(NotMatchedOptional::class, $forFirst);
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
        $callback = function (Match $match) {
            return str_split(str_repeat($match, 2));
        };

        // when
        $flatMap = $matchPattern->flatMap($callback);

        // then
        $this->assertEquals(['a', 'a', 'b', 'b', '', 'c', 'c'], $flatMap);
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
        $this->assertEquals([1, 4, 12, 15], $offsets);
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
        $this->assertEquals(1, $offset);
    }

    /**
     * @test
     */
    public function shouldMap()
    {
        // given
        $matchPattern = $this->standardMatchPattern();
        $mapper = function (Match $match) {
            return lcfirst($match) . ucfirst($match);
        };

        // when
        $mapped = $matchPattern->map($mapper);

        // then
        $this->assertEquals(['', 'aA', '', 'cC'], $mapped);
    }

    /**
     * @test
     */
    public function shouldForFirst()
    {
        // given
        $matchPattern = $this->standardMatchPattern();
        $callback = function (Match $match) {
            return 'for first: ' . $match->text();
        };

        // when
        $first = $matchPattern->forFirst($callback)->orReturn('');

        // then
        $this->assertEquals('for first: ', $first);
    }

    /**
     * @test
     */
    public function shouldChain_filter()
    {
        // given
        $pattern = '\w+';
        $subject = '...you forgot one very important thing mate.';
        $predicate = function (Match $match) {
            return $match->text() != 'forgot';
        };
        $pattern = new FilteredMatchPattern(new FilteredBaseDecorator(new ApiBase(new InternalPattern($pattern), $subject, new UserData()), new Predicate($predicate)));

        // when
        $filtered = $pattern
            ->filter(function (Match $match) {
                return $match->text() != 'very';
            })
            ->filter(function (Match $match) {
                return $match->text() != 'mate';
            })
            ->all();

        // then
        $this->assertEquals(['you', 'one', 'important', 'thing'], $filtered);
    }

    /**
     * @test
     */
    public function shouldForEach()
    {
        // given
        $matchPattern = $this->standardMatchPattern();
        $matches = [];
        $callback = function (Match $match) use (&$matches) {
            $matches[] = $match->text();
        };

        // when
        $matchPattern->forEach($callback);

        // then
        $this->assertEquals(['', 'a', '', 'c'], $matches);
    }

    /**
     * @test
     */
    public function shouldGet_iterator()
    {
        // given
        $matchPattern = $this->standardMatchPattern();

        // when
        $iterator = $matchPattern->iterator();

        // then
        $array = [];
        foreach ($iterator as $match) {
            $array[] = $match->text();
        }
        $this->assertEquals(['', 'a', '', 'c'], $array);
    }

    private function standardMatchPattern(): AbstractMatchPattern
    {
        return $this->matchPattern(function (Match $match) {
            return $match->text() !== 'b';
        });
    }

    private function standardMatchPattern_all(): AbstractMatchPattern
    {
        return $this->matchPattern(function () {
            return true;
        });
    }

    private function standardMatchPattern_notFirst(): AbstractMatchPattern
    {
        return $this->matchPattern(function (Match $match) {
            return $match->index() > 0;
        });
    }

    private function standardMatchPattern_filtered(): AbstractMatchPattern
    {
        return $this->matchPattern(function () {
            return false;
        });
    }

    private function matchPattern(callable $predicate): AbstractMatchPattern
    {
        return new FilteredMatchPattern(
            new FilteredBaseDecorator(
                new ApiBase(
                    new InternalPattern($this->pattern()),
                    $this->subject(),
                    new UserData()
                ),
                new Predicate($predicate)
            )
        );
    }

    private function pattern(): string
    {
        return '(?<=\()[a-z]?(?=\))';
    }

    private function subject(): string
    {
        return '() (a) (b) () (c)';
    }
}

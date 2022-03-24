<?php
namespace Test\Feature\TRegx\CleanRegex\Match\stream;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsSameMatches;
use Test\Utils\CustomSubjectException;
use Test\Utils\Definitions;
use Test\Utils\DetailFunctions;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\InvalidIntegerTypeException;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Exception\NoSuchStreamElementException;
use TRegx\CleanRegex\Internal\Match\Stream\EmptyStreamException;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\Group\Group;
use TRegx\CleanRegex\Match\MatchPattern;

class MatchPatternTest extends TestCase
{
    use AssertsSameMatches;

    /**
     * @test
     */
    public function shouldGetAllTexts()
    {
        // given
        $stream = pattern('\d+')->match('123,456,789')->stream();

        // when
        $all = $stream->all();

        // then
        $this->assertSame('123', $all[0]->text());
        $this->assertSame('456', $all[1]->text());
        $this->assertSame('789', $all[2]->text());
    }

    /**
     * @test
     */
    public function shouldGetFirstTextAndIndex()
    {
        // given
        $stream = pattern('\d+')->match('123,456,789')->stream();

        // when
        $first = $stream->first();

        // then
        $this->assertSame('123', $first->text());
        $this->assertSame(0, $first->index());
    }

    /**
     * @test
     */
    public function shouldGetFirstKey()
    {
        // given
        $stream = pattern('\d+')->match('123,456,789')->stream();

        // when
        $key = $stream->keys()->first();

        // then
        $this->assertSame(0, $key);
    }

    /**
     * @test
     */
    public function shouldGetAllIndexes()
    {
        // given
        $stream = pattern('\d+')->match('123,456,789')->stream();

        // when
        $all = $stream->all();

        // then
        $this->assertSame(0, $all[0]->index());
        $this->assertSame(1, $all[1]->index());
        $this->assertSame(2, $all[2]->index());
    }

    /**
     * @test
     */
    public function shouldGetFirstOtherTexts()
    {
        // given
        $stream = pattern('\d+')->match('123,456,789')->stream();

        // when
        $first = $stream->first();

        // then
        $this->assertSame(['123', '456', '789'], $first->all());
    }

    /**
     * @test
     */
    public function shouldGetAllOtherTexts()
    {
        // given
        $stream = pattern('\d+')->match('123,456,789')->stream();

        // when
        [$first, $second, $third] = $stream->all();

        // then
        $this->assertSame(['123', '456', '789'], $first->all());
        $this->assertSame(['123', '456', '789'], $second->all());
        $this->assertSame(['123', '456', '789'], $third->all());
    }

    /**
     * @test
     */
    public function test()
    {
        // when
        $result = pattern("(?<capital>[A-Z])?[a-zA-Z']+")
            ->match("I'm rather old, He likes Apples")
            ->stream()
            ->filter(function (Detail $detail) {
                return $detail->textLength() !== 3;
            })
            ->map(function (Detail $detail) {
                return $detail->group('capital');
            })
            ->map(function (Group $detailGroup) {
                if ($detailGroup->matched()) {
                    return "yes: $detailGroup";
                }
                return "no";
            })
            ->values()
            ->all();

        // then
        $this->assertSame(['no', 'yes: H', 'no', 'yes: A'], $result);
    }

    /**
     * @test
     */
    public function shouldGetGroupNames_lastGroup()
    {
        // when
        pattern('Foo(?<one>Bar)?(?<two>Bar)?')
            ->match('Foo')
            ->stream()
            ->first(function (Detail $detail) {
                $this->assertEquals(['one', 'two'], $detail->groupNames());
                $this->assertTrue($detail->hasGroup('one'));
            });
    }

    /**
     * @test
     */
    public function shouldPassUserData()
    {
        // given
        pattern("(Foo|Bar)")
            ->match("Foo, Bar")
            ->stream()
            ->filter(function (Detail $detail) {
                // when
                $detail->setUserData("$detail" === 'Foo' ? 'hey' : 'hello');

                return true;
            })
            ->forEach(function (Detail $detail) {
                // then
                $this->assertSame($detail->index() === 0 ? 'hey' : 'hello', $detail->getUserData());
            });
    }

    /**
     * @test
     */
    public function should_findFirst_orThrow()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage("Expected to get the first match, but subject was not matched");

        // when
        pattern('Foo')->match('Bar')->stream()->findFirst(Functions::fail())->orThrow();
    }

    /**
     * @test
     */
    public function should_keys_findFirst_orThrow()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');

        // when
        pattern('Foo')->match('Bar')->stream()->keys()->findFirst(Functions::fail())->orThrow();
    }

    /**
     * @test
     */
    public function should_findFirst_orThrow_custom()
    {
        try {
            // when
            pattern("Foo")
                ->match("Bar")
                ->stream()
                ->findFirst(Functions::fail())
                ->orThrow(CustomSubjectException::class);
        } catch (CustomSubjectException $exception) {
            // then
            $this->assertSame('Expected to get the first match, but subject was not matched', $exception->getMessage());
            $this->assertSame('Bar', $exception->subject);
        }
    }

    /**
     * @test
     */
    public function should_filter_findFirst_orThrow_custom()
    {
        try {
            // when
            pattern('Foo')
                ->match('Foo')
                ->stream()
                ->filter(Functions::constant(false))
                ->findFirst(Functions::fail())
                ->orThrow(CustomSubjectException::class);
        } catch (CustomSubjectException $exception) {
            // then
            $this->assertSame('Expected to get the first stream element, but the stream has 0 element(s)', $exception->getMessage());
            $this->assertSame('Foo', $exception->subject);
        }
    }

    /**
     * @test
     */
    public function should_findFirst_orElse()
    {
        // when
        pattern('Foo')->match('Bar')->stream()->findFirst(Functions::fail())->orElse(Functions::argumentless());
    }

    /**
     * @test
     */
    public function should_findFirst_orValue()
    {
        // when
        $value = pattern('Foo')->match('Bar')->stream()->findFirst(Functions::fail())->orReturn('value');

        // then
        $this->assertSame('value', $value);
    }

    /**
     * @test
     */
    public function should_filter_findFirst_orElse()
    {
        // when
        pattern('Foo')
            ->match('Foo')
            ->stream()
            ->filter(Functions::constant(false))
            ->findFirst(Functions::fail())
            ->orElse(Functions::argumentless());
    }

    /**
     * @test
     */
    public function should_first_throw_InternalRegxException()
    {
        try {
            // when
            pattern("Foo")->match("Foo")->stream()->first(Functions::throws(new EmptyStreamException()));
        } catch (EmptyStreamException $exception) {
            // then
            $this->assertEmpty($exception->getMessage());
        }
    }

    /**
     * @test
     */
    public function should_findFirst_orThrow_InternalRegxException()
    {
        try {
            // when
            pattern("Foo")->match("Foo")->stream()->findFirst(Functions::throws(new EmptyStreamException()))->orThrow();
        } catch (EmptyStreamException $exception) {
            // then
            $this->assertEmpty($exception->getMessage());
        }
    }

    /**
     * @test
     */
    public function should_findFirstCallback_orThrow()
    {
        // when
        $letters = pattern('Foo')->match('Foo')->stream()->findFirst(Functions::letters())->orThrow();

        // then
        $this->assertSame(['F', 'o', 'o'], $letters);
    }

    /**
     * @test
     */
    public function shouldThrow_filter_all_onInvalidReturnType()
    {
        // given
        $pattern = new MatchPattern(Definitions::pattern('Foo'), new Subject('Foo'));

        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid filter() callback return type. Expected bool, but integer (45) given');

        // when
        $pattern->stream()->filter(Functions::constant(45))->all();
    }

    /**
     * @test
     */
    public function shouldThrow_filter_first_onInvalidReturnType()
    {
        // given
        $pattern = new MatchPattern(Definitions::pattern('Foo'), new Subject('Foo'));
        $stream = $pattern->stream()->filter(Functions::constant(45));

        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid filter() callback return type. Expected bool, but integer (45) given');

        // when
        $stream->first();
    }

    /**
     * @test
     */
    public function shouldThrowForUnparsableEntity()
    {
        // given
        $stream = pattern('\d+')->match('123')->stream()->map(Functions::constant(null))->asInt();

        // when
        $this->expectException(InvalidIntegerTypeException::class);
        $this->expectExceptionMessage('Failed to parse value as integer. Expected integer|string, but null given');

        // when
        $stream->first();
    }

    /**
     * @test
     */
    public function shouldBeCountable()
    {
        // given
        $stream = pattern('\d+')->match('1, 2, 3')->stream();

        // when
        $count = \count($stream);

        // then
        $this->assertSame(3, $count);
    }

    /**
     * @test
     */
    public function shouldBeIterable()
    {
        // given
        $stream = pattern('\d+([cm]?m)')->match('14cm 12mm 18m')->stream();

        // when
        $result = \iterator_to_array($stream);

        // then
        $this->assertSameMatches(['14cm', '12mm', '18m'], $result);
    }

    /**
     * @test
     */
    public function shouldMapToIntegerDifferentBaseBefore()
    {
        // when
        $result = pattern('\d+')->match('12, 15, 16')->asInt(16)->stream()->all();

        // then
        $this->assertSameMatches([18, 21, 22], $result);
    }

    /**
     * @test
     */
    public function shouldMapToIntegerDifferentBaseAfter()
    {
        // when
        $result = pattern('\d+')->match('12, 15, 16')->stream()->asInt(16)->all();

        // then
        $this->assertSameMatches([18, 21, 22], $result);
    }

    /**
     * @test
     */
    public function shouldMapToIntegerDifferentBaseAfterMapped()
    {
        // when
        $result = pattern('\d+')->match('12, 15, 16')->stream()->map(DetailFunctions::text())->asInt(16)->all();

        // then
        $this->assertSameMatches([18, 21, 22], $result);
    }
}

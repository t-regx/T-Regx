<?php
namespace Test\Feature\CleanRegex\Match\flatMap;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use Test\Utils\Functions;
use Test\Utils\Structure\AssertsStructure;
use Test\Utils\Structure\Expect;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern
 */
class MatchPatternTest extends TestCase
{
    use TestCasePasses, AssertsDetail, AssertsStructure;

    /**
     * @test
     */
    public function shouldFlatMap()
    {
        // given
        $match = Pattern::of('\w+')->match('Winter is coming');
        // when
        $flatMap = $match->flatMap(Functions::letters());
        // then
        $expected = [
            'W', 'i', 'n', 't', 'e', 'r',
            'i', 's',
            'c', 'o', 'm', 'i', 'n', 'g'
        ];
        $this->assertSame($expected, $flatMap);
    }

    /**
     * @test
     */
    public function shouldFlatMap_withKeys()
    {
        // given
        $match = Pattern::of('\w+')->match('Family, Duty, Honor');
        // when
        $flatMap = $match->flatMap(function (Detail $detail) {
            return [$detail->text() => $detail->offset()];
        });
        // then
        $this->assertSame([0, 8, 14], $flatMap);
    }

    /**
     * @test
     */
    public function shouldFlatMap_withDuplicateKeys()
    {
        // given
        $match = Pattern::of('\w+')->match('Family, Duty, Honor');
        // when
        $flatMap = $match->flatMap(function (Detail $detail) {
            return ['duplicate' => $detail->offset()];
        });
        // then
        $this->assertSame([0, 8, 14], $flatMap);
    }

    /**
     * @test
     */
    public function shouldFlatMap_withDetails()
    {
        // given
        $match = Pattern::of('\w+')->match("Hear me roar");
        // when
        $match->flatMap(Functions::collect($details, []));
        // then
        $this->assertStructure($details, [
            Expect::text('Hear'),
            Expect::text('me'),
            Expect::text('roar'),
        ]);
        $this->assertDetailsIndexed(...$details);
        $this->assertDetailsAll(['Hear', 'me', 'roar'], ...$details);
    }

    /**
     * @test
     */
    public function shouldFlatMap_withDetails_identity()
    {
        // given
        $match = Pattern::of('\w+')->match("Hear me roar");
        // when
        $details = $match->flatMap(Functions::wrap());
        // then
        [$hear, $me, $roar] = $details;
        $this->assertDetailText('Hear', $hear);
        $this->assertDetailText('me', $me);
        $this->assertDetailText('roar', $roar);
        $this->assertDetailsIndexed(...$details);
        $this->assertDetailsAll(['Hear', 'me', 'roar'], ...$details);
    }

    /**
     * @test
     */
    public function shouldNotInvoke_onNotMatchingSubject()
    {
        // given
        $match = Pattern::of('Foo')->match('Bar');
        // when
        $match->flatMap(Functions::fail());
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldReturnEmptyArray_onUnmatchedSubject()
    {
        // given
        $match = Pattern::of('Foo')->match('Bar');
        // when
        $map = $match->flatMap(Functions::fail());
        // then
        $this->assertEmpty($map);
    }

    /**
     * @test
     */
    public function shouldThrow_onNonArrayReturnType()
    {
        // given
        $match = Pattern::of('Foo')->match('Foo');
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage("Invalid flatMap() callback return type. Expected array, but string ('string') given");
        // when
        $match->flatMap(Functions::constant('string'));
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidPattern()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        Pattern::of('+')->match('Foo')->flatMap(Functions::fail());
    }

    /**
     * @test
     */
    public function shouldFlatMapArrays()
    {
        // given
        $match = Pattern::of('Foo')->match('Foo');
        // when
        $result = $match->flatMap(Functions::constant(['a', ['b'], [['c']]]));
        // then
        $this->assertSame(['a', ['b'], [['c']]], $result);
    }
}

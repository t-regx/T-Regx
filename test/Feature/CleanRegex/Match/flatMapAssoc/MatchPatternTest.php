<?php
namespace Test\Feature\CleanRegex\Match\flatMapAssoc;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use Test\Utils\Functions;
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
    use TestCasePasses, AssertsDetail;

    /**
     * @test
     */
    public function shouldFlatMapAssoc()
    {
        // given
        $match = Pattern::of('\w+')->match('One, Two, Six');
        // when
        $dictionary = $match->flatMapAssoc(Functions::lettersAsKeys());
        // then
        $expected = [
            'O' => 0,
            'n' => 1,
            'e' => 2,
            'T' => 0,
            'w' => 1,
            'o' => 2,
            'S' => 0,
            'i' => 1,
            'x' => 2,
        ];
        $this->assertSame($expected, $dictionary);
    }

    /**
     * @test
     */
    public function shouldFlatMapAssocReassignedKeys()
    {
        // when
        $dictionary = pattern('[A-Za-z]+')->match('Docker, Down, Foo')->flatMapAssoc(Functions::letters());
        // then
        $this->assertSame(['F', 'o', 'o', 'n', 'e', 'r'], $dictionary);
    }

    /**
     * @test
     */
    public function shouldFlatMap_withKeys()
    {
        // given
        $match = Pattern::of('\w+')->match('Family, Duty, Honor');
        // when
        $dictionary = $match->flatMapAssoc(function (Detail $detail) {
            return [$detail->text() => $detail->offset()];
        });
        // then
        $expected = [
            'Family' => 0,
            'Duty'   => 8,
            'Honor'  => 14
        ];
        $this->assertSame($expected, $dictionary);
    }

    /**
     * @test
     */
    public function shouldFlatMap_withDuplicateKeysString()
    {
        // given
        $match = Pattern::of('\w+')->match('Family, Duty, Honor');
        // when
        $dictionary = $match->flatMapAssoc(function (Detail $detail) {
            return ['duplicate' => $detail->offset()];
        });
        // then
        $this->assertSame(['duplicate' => 14], $dictionary);
    }

    /**
     * @test
     */
    public function shouldFlatMap_withDuplicateKeysInteger()
    {
        // given
        $match = Pattern::of('\w+')->match('Family, Duty, Honor');
        // when
        $dictionary = $match->flatMapAssoc(function (Detail $detail) {
            return [1 => $detail->offset()];
        });
        // then
        $this->assertSame([1 => 14], $dictionary);
    }

    /**
     * @test
     */
    public function shouldFlatMap_withDetails()
    {
        // given
        $match = Pattern::of('\w+')->match("Hear me roar");
        // when
        $match->flatMapAssoc(Functions::collect($details, []));
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
    public function shouldFlatMap_withDetails_identity()
    {
        // given
        $match = Pattern::of('\w+')->match("Hear me roar");
        // when
        $details = $match->flatMapAssoc(Functions::wrapKeySequential());
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
        $match->flatMapAssoc(Functions::fail());
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
        $map = $match->flatMapAssoc(Functions::fail());
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
        $this->expectExceptionMessage("Invalid flatMapAssoc() callback return type. Expected array, but string ('string') given");
        // when
        $match->flatMapAssoc(Functions::constant('string'));
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
        Pattern::of('+')->match('Foo')->flatMapAssoc(Functions::fail());
    }

    /**
     * @test
     */
    public function shouldFlatMapArrays()
    {
        // given
        $match = Pattern::of('Foo')->match('Foo');
        // when
        $result = $match->flatMapAssoc(Functions::constant(['a', 1 => ['b'], 2 => [['c']]]));
        // then
        $this->assertSame(['a', ['b'], [['c']]], $result);
    }
}

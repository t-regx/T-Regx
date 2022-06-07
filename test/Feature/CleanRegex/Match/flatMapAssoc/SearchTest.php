<?php
namespace Test\Feature\CleanRegex\Match\flatMapAssoc;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use Test\Utils\Functions;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

/**
 * @covers \TRegx\CleanRegex\Match\Search
 */
class SearchTest extends TestCase
{
    use TestCasePasses, AssertsDetail;

    /**
     * @test
     */
    public function shouldFlatMapAssoc()
    {
        // given
        $search = Pattern::of('\w+')->search('One, Two, Six');
        // when
        $dictionary = $search->flatMapAssoc(Functions::lettersAsKeys());
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
        $dictionary = pattern('[A-Za-z]+')->search('Docker, Down, Foo')->flatMapAssoc(Functions::letters());
        // then
        $this->assertSame(['F', 'o', 'o', 'n', 'e', 'r'], $dictionary);
    }

    /**
     * @test
     */
    public function shouldFlatMap_withKeys()
    {
        // given
        $search = Pattern::of('\w+')->search('Family, Duty, Honor');
        // when
        $dictionary = $search->flatMapAssoc(function ($text) {
            return [$text => $text];
        });
        // then
        $expected = [
            'Family' => 'Family',
            'Duty'   => 'Duty',
            'Honor'  => 'Honor'
        ];
        $this->assertSame($expected, $dictionary);
    }

    /**
     * @test
     */
    public function shouldFlatMap_withDuplicateKeysString()
    {
        // given
        $search = Pattern::of('\w+')->search('Family, Duty, Honor');
        // when
        $dictionary = $search->flatMapAssoc(function ($text) {
            return ['duplicate' => $text];
        });
        // then
        $this->assertSame(['duplicate' => 'Honor'], $dictionary);
    }

    /**
     * @test
     */
    public function shouldFlatMap_withDuplicateKeysInteger()
    {
        // given
        $search = Pattern::of('\w+')->search('Family, Duty, Honor');
        // when
        $dictionary = $search->flatMapAssoc(function ($string) {
            return [1 => $string];
        });
        // then
        $this->assertSame([1 => 'Honor'], $dictionary);
    }

    /**
     * @test
     */
    public function shouldFlatMap_withDetails()
    {
        // given
        $search = Pattern::of('\w+')->search('Hear me roar');
        // when
        $search->flatMapAssoc(Functions::collect($texts, []));
        // then
        $this->assertSame(['Hear', 'me', 'roar'], $texts);
    }

    /**
     * @test
     */
    public function shouldFlatMap_withDetails_identity()
    {
        // given
        $search = Pattern::of('\w+')->search('Hear me roar');
        // when
        $texts = $search->flatMapAssoc(Functions::wrapKeySequential(3));
        // then
        $this->assertSame([3 => 'Hear', 4 => 'me', 5 => 'roar'], $texts);
    }

    /**
     * @test
     */
    public function shouldNotInvoke_onNotMatchingSubject()
    {
        // given
        $search = Pattern::of('Foo')->search('Bar');
        // when
        $search->flatMapAssoc(Functions::fail());
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldReturnEmptyArray_onUnmatchedSubject()
    {
        // given
        $search = Pattern::of('Foo')->search('Bar');
        // when
        $map = $search->flatMapAssoc(Functions::fail());
        // then
        $this->assertEmpty($map);
    }

    /**
     * @test
     */
    public function shouldThrow_onNonArrayReturnType()
    {
        // given
        $match = Pattern::of('Foo')->search('Foo');
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
        Pattern::of('+')->search('Foo')->flatMapAssoc(Functions::fail());
    }

    /**
     * @test
     */
    public function shouldFlatMapArrays()
    {
        // given
        $search = Pattern::of('Foo')->search('Foo');
        // when
        $result = $search->flatMapAssoc(Functions::constant(['a', 1 => ['b'], 2 => [['c']]]));
        // then
        $this->assertSame(['a', ['b'], [['c']]], $result);
    }
}

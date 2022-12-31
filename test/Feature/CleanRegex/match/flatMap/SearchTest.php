<?php
namespace Test\Feature\CleanRegex\match\flatMap;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use Test\Utils\Functions;
use Test\Utils\TestCase\TestCasePasses;
use Test\Utils\TypeFunctions;
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
    public function shouldFlatMap()
    {
        // given
        $search = Pattern::of('\w+')->search('Winter is coming');
        // when
        $flatMap = $search->flatMap(Functions::letters());
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
        $search = Pattern::of('\w+')->search('Family, Duty, Honor');
        // when
        $flatMap = $search->flatMap(function (string $text) {
            return [$text => $text];
        });
        // then
        $this->assertSame(['Family', 'Duty', 'Honor'], $flatMap);
    }

    /**
     * @test
     */
    public function shouldFlatMap_withDuplicateKeys()
    {
        // given
        $search = Pattern::of('\w+')->search('Family, Duty, Honor');
        // when
        $flatMap = $search->flatMap(function (string $text) {
            return ['duplicate' => $text];
        });
        // then
        $this->assertSame(['Family', 'Duty', 'Honor'], $flatMap);
    }

    /**
     * @test
     */
    public function shouldFlatMap_withTypeString()
    {
        // given
        $search = Pattern::of('\w+')->search('We do not sow');
        // when
        $search->flatMap(TypeFunctions::assertTypeString([]));
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldFlatMap_withString()
    {
        // given
        $search = Pattern::of('\w+')->search('Hear me roar');
        // when
        $result = $search->flatMap(function (string $value): array {
            return [\strToUpper($value)];
        });
        // then
        $this->assertSame(['HEAR', 'ME', 'ROAR'], $result);
    }

    /**
     * @test
     */
    public function shouldFlatMap_withString_identity()
    {
        // given
        $search = Pattern::of('\w+')->search("Hear me roar");
        // when
        $texts = $search->flatMap(Functions::wrap());
        // then
        $this->assertSame(['Hear', 'me', 'roar'], $texts);
    }

    /**
     * @test
     */
    public function shouldNotInvoke_onNotMatchingSubject()
    {
        // given
        $search = Pattern::of('Foo')->search('Bar');
        // when
        $search->flatMap(Functions::fail());
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
        $map = $search->flatMap(Functions::fail());
        // then
        $this->assertEmpty($map);
    }

    /**
     * @test
     */
    public function shouldThrow_onNonArrayReturnType()
    {
        // given
        $search = Pattern::of('Foo')->search('Foo');
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage("Invalid flatMap() callback return type. Expected array, but string ('string') given");
        // when
        $search->flatMap(Functions::constant('string'));
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
        Pattern::of('+')->search('Foo')->flatMap(Functions::fail());
    }

    /**
     * @test
     */
    public function shouldFlatMapArrays()
    {
        // given
        $search = Pattern::of('Foo')->search('Foo');
        // when
        $result = $search->flatMap(Functions::constant(['a', ['b'], [['c']]]));
        // then
        $this->assertSame(['a', ['b'], [['c']]], $result);
    }
}

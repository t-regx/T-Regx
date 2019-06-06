<?php
namespace Test\Unit\TRegx\CleanRegex\Replace\Map;

use PHPUnit\Framework\TestCase;
use Test\Feature\TRegx\CleanRegex\Replace\by\group\CustomException;
use TRegx\CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use TRegx\CleanRegex\Exception\CleanRegex\Messages\Group\ReplacementWithUnmatchedGroupMessage;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\SubjectableImpl;
use Test\Utils\ComputedMapper;
use TRegx\CleanRegex\Replace\GroupMapper\GroupMapper;
use TRegx\CleanRegex\Replace\GroupMapper\IdentityMapper;
use Test\Utils\NoReplacementMapper;
use TRegx\CleanRegex\Replace\GroupMapper\DictionaryMapper;
use TRegx\CleanRegex\Replace\By\GroupFallbackReplacer;
use TRegx\CleanRegex\Replace\NonReplaced\ConstantResultStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\CustomThrowStrategy;

class GroupFallbackReplacerTest extends TestCase
{
    /**
     * @test
     * @happyPath
     * @dataProvider strategies
     * @param GroupMapper $mapper
     * @param $expected
     */
    public function shouldReplace_usingStrategy(GroupMapper $mapper, $expected)
    {
        // given
        $mapReplacer = $this->create('\[(\w+)\]', '[two], [three], [four]');

        // when
        $result = $mapReplacer->replaceOrFallback(1, $mapper, new DefaultStrategy());

        // then
        $this->assertEquals($expected, $result);
    }

    function strategies(): array
    {
        return [
            'computed' => [
                new ComputedMapper('strlen'),
                '3, 5, 4'
            ],
            'identity' => [
                new IdentityMapper(),
                'two, three, four'
            ],
            'ignoring' => [
                new NoReplacementMapper(),
                '[two], [three], [four]'
            ],
            'map'      => [
                new DictionaryMapper(['two' => 'dwa', 'three' => 'trzy', 'four' => 'cztery']),
                'dwa, trzy, cztery'
            ]
        ];
    }

    /**
     * @test
     */
    public function shouldReplace_emptyString()
    {
        // given
        $fallbackReplacer = $this->create('\[(\w*)\]', '[two] [] [four]');

        // when
        $result = $fallbackReplacer->replaceOrFallback(1,
            new ComputedMapper('strlen'),
            new DefaultStrategy());

        // then
        $this->assertEquals('3 0 4', $result);
    }

    /**
     * @test
     */
    public function shouldFallback_toStrategy_unmatchedGroup()
    {
        // given
        $fallbackReplacer = $this->create('\[(\w+)?\]', '[two] [] [four]');

        // when
        $result = $fallbackReplacer->replaceOrFallback(1, new NoReplacementMapper(), new ConstantResultStrategy('fallback'));

        // then
        $this->assertEquals('[two] fallback [four]', $result);
    }

    /**
     * @test
     */
    public function shouldFallback_toDefault()
    {
        // given
        $fallbackReplacer = $this->create('\[(\w+)\]', '');

        // when
        $result = $fallbackReplacer->replaceOrFallback(1, new NoReplacementMapper(), new DefaultStrategy());

        // then
        $this->assertEquals('Subject not matched', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidGroup()
    {
        // given
        $fallbackReplacer = $this->create('', '');

        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: '1'");

        // when
        $fallbackReplacer->replaceOrFallback(1, new NoReplacementMapper(), new DefaultStrategy());
    }

    /**
     * @test
     */
    public function shouldThrow_forUnmatchedGroup_last()
    {
        // given
        $fallbackReplacer = $this->create('word:(\d)?', 'word:');

        // then
        $this->expectException(CustomException::class);
        $this->expectExceptionMessage("Expected to replace with group '1', but the group was not matched");

        // when
        $fallbackReplacer->replaceOrFallback(
            1,
            new NoReplacementMapper(),
            new CustomThrowStrategy(CustomException::class, new ReplacementWithUnmatchedGroupMessage(1))
        );
    }

    /**
     * @test
     */
    public function shouldThrow_forUnmatchedGroup_middle()
    {
        // given
        $fallbackReplacer = $this->create('foo:(\d)?:(bar)', 'foo::bar');

        // then
        $this->expectException(CustomException::class);
        $this->expectExceptionMessage("Expected to replace with group '1', but the group was not matched");

        // when
        $fallbackReplacer->replaceOrFallback(
            1,
            new NoReplacementMapper(),
            new CustomThrowStrategy(CustomException::class, new ReplacementWithUnmatchedGroupMessage(1))
        );
    }

    public function create($pattern, $subject): GroupFallbackReplacer
    {
        return new GroupFallbackReplacer(
            new InternalPattern($pattern),
            new SubjectableImpl($subject),
            -1,
            new ConstantResultStrategy('Subject not matched'),
            new ApiBase(new InternalPattern($pattern), $subject, new UserData())
        );
    }
}

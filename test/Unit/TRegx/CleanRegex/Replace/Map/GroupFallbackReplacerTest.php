<?php
namespace Test\Unit\TRegx\CleanRegex\Replace\Map;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\SubjectableImpl;
use TRegx\CleanRegex\Replace\Map\GroupFallbackReplacer;
use TRegx\CleanRegex\Replace\NonReplaced\ComputedSubjectStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\ConstantResultStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\DefaultStrategy;

class GroupFallbackReplacerTest extends TestCase
{
    /**
     * @test
     * @happyPath
     */
    public function shouldReplace_usingStrategy()
    {
        // given
        $mapReplacer = $this->create('\[(\w+)\]', '[two] [three] [four]');

        // when
        $result = $mapReplacer->replaceOrFallback(1, new ComputedSubjectStrategy('strlen'), function () {
        });

        // then
        $this->assertEquals('3 5 4', $result);
    }

    /**
     * @test
     */
    public function shouldReplace_emptyString()
    {
        // given
        $mapReplacer = $this->create('\[(\w*)\]', '[two] [] [four]');

        // when
        $result = $mapReplacer->replaceOrFallback(1, new ComputedSubjectStrategy('strlen'), function () {
        });

        // then
        $this->assertEquals('3 0 4', $result);
    }

    /**
     * @test
     */
    public function shouldFallback_toStrategy()
    {
        // given
        $mapReplacer = $this->create('\[(\w+)\]', '[two] [three] [four]');

        // when
        $result = $mapReplacer->replaceOrFallback(1, new DefaultStrategy(), function ($match, $group) {
            return strrev($group);
        });

        // then
        $this->assertEquals('owt eerht ruof', $result);
    }

    /**
     * @test
     */
    public function shouldFallback_toDefault()
    {
        // given
        $mapReplacer = $this->create('\[(\w+)\]', '');

        // when
        $result = $mapReplacer->replaceOrFallback(1, new DefaultStrategy(), function () {
        });

        // then
        $this->assertEquals('Group not found', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidGroup()
    {
        // given
        $mapReplacer = $this->create('', '');

        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: '1'");

        // when
        $mapReplacer->replaceOrFallback(1, new DefaultStrategy(), function () {
        });
    }

    /**
     * @test
     */
    public function shouldThrow_forUnmatchedGroup_last()
    {
        // given
        $mapReplacer = $this->create('word:(\d)?', 'word:');

        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to replace with group '1', but the group was not matched");

        // when
        $mapReplacer->replaceOrFallback(1, new DefaultStrategy(), function () {
        });
    }

    /**
     * @test
     */
    public function shouldThrow_forUnmatchedGroup_middle()
    {
        // given
        $mapReplacer = $this->create('foo:(\d)?:(bar)', 'foo::bar');

        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to replace with group '1', but the group was not matched");

        // when
        $mapReplacer->replaceOrFallback(1, new DefaultStrategy(), function () {
        });
    }

    public function create($pattern, $subject): GroupFallbackReplacer
    {
        return new GroupFallbackReplacer(
            new InternalPattern($pattern),
            new SubjectableImpl($subject),
            -1,
            new ConstantResultStrategy('Group not found'),
            new ApiBase(new InternalPattern($pattern), $subject, new UserData())
        );
    }
}

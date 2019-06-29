<?php
namespace Test\Feature\TRegx\CleanRegex\Match\fluent;

use PHPUnit\Framework\TestCase;
use Test\Feature\TRegx\CleanRegex\Replace\by\group\CustomException;
use TRegx\CleanRegex\Exception\CleanRegex\NoFirstElementFluentException;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;
use TRegx\CleanRegex\Match\Details\Match;

class AbstractMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldFluent()
    {
        // when
        $result = pattern("(?<capital>[A-Z])?[\w']+")
            ->match("I'm rather old, He likes Apples")
            ->fluent()
            ->filter(function (Match $match) {
                return $match->textLength() !== 3;
            })
            ->map(function (Match $match) {
                return $match->group('capital');
            })
            ->map(function (MatchGroup $matchGroup) {
                if ($matchGroup->matched()) {
                    return "yes: $matchGroup";
                }
                return "no";
            })
            ->all();

        // then
        $this->assertEquals(['no', 'yes: H', 'no', 'yes: A'], $result);
    }

    /**
     * @test
     */
    public function shouldFluent_forFirst()
    {
        // when
        pattern("(?<capital>[A-Z])?[\w']+")
            ->match("I'm rather old, He likes Apples")
            ->fluent()
            ->filter(function (Match $match) {
                return $match->textLength() !== 3;
            })
            ->forFirst(function (Match $match) {
                $this->assertTrue(true);
            })
            ->orThrow();
    }

    /**
     * @test
     */
    public function shouldFluent_forFirst_orThrow()
    {
        // then
        $this->expectException(NoFirstElementFluentException::class);
        $this->expectExceptionMessage("Expected to get the first element from fluent pattern, but the elements feed is empty");

        // when
        pattern("Foo")
            ->match("Bar")
            ->fluent()
            ->forFirst(function (Match $match) {
                $this->assertTrue(false);
            })
            ->orThrow();
    }

    /**
     * @test
     */
    public function shouldFluent_forFirst_orThrow_custom()
    {
        // then
        $this->expectException(CustomException::class);
        $this->expectExceptionMessage("Expected to get the first element from fluent pattern, but the elements feed is empty");

        // when
        pattern("Foo")
            ->match("Bar")
            ->fluent()
            ->forFirst(function (Match $match) {
                $this->assertTrue(false);
            })
            ->orThrow(CustomException::class);
    }
}

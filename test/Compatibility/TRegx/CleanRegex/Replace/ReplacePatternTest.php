<?php
namespace Test\Compatibility\TRegx\CleanRegex\Replace;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;
use TRegx\CleanRegex\Match\Details\Group\ReplaceMatchedGroup;
use TRegx\CleanRegex\Match\Details\Group\ReplaceMatchGroup;
use TRegx\CleanRegex\Match\Details\Group\ReplaceNotMatchedGroup;
use TRegx\CleanRegex\Match\Details\LazyDetailImpl;
use TRegx\CleanRegex\Match\Details\Match;
use TRegx\CleanRegex\Match\Details\ReplaceMatch;
use TRegx\CleanRegex\Match\Details\ReplaceMatchImpl;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCallAsMatch()
    {
        // when
        pattern('[A-Z][a-z]+')->replace('First, Second, Third, Fourth, Fifth')
            ->first()
            ->callback(function (Match $match) {
                // then
                $this->assertInstanceOf(ReplaceMatch::class, $match);
                $this->assertInstanceOf(ReplaceMatchImpl::class, $match);

                // cleanup
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldCallAsMatchGroup_matched()
    {
        // when
        pattern('[A-Z][a-z]+')->replace('First, Second, Third, Fourth, Fifth')
            ->first()
            ->by()
            ->group(0)
            ->callback(function (MatchGroup $group) {
                // then
                $this->assertInstanceOf(ReplaceMatchGroup::class, $group);
                $this->assertInstanceOf(ReplaceMatchedGroup::class, $group);

                // cleanup
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldCallAsMatchGroup_notMatched()
    {
        // when
        pattern('Foo(Bar)?')->replace('Foo')
            ->first()
            ->by()
            ->group(1)
            ->callback(function (MatchGroup $group) {
                // then
                $this->assertInstanceOf(ReplaceMatchGroup::class, $group);
                $this->assertInstanceOf(ReplaceNotMatchedGroup::class, $group);

                // cleanup
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldCallAsMatch_LazyDetailImpl()
    {
        // when
        pattern('Foo(Bar)?')->replace('Foo')
            ->first()
            ->by()
            ->group(1)
            ->orElseCalling(function (Match $match) {
                // then
                $this->assertInstanceOf(LazyDetailImpl::class, $match);

                // cleanup
                return '';
            });
    }
}

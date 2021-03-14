<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Model\Match;

use PHPUnit\Framework\TestCase;
use Test\Utils\Internal;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\LazyRawWithGroups;

class LazyRawWithGroupsTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotCallAll()
    {
        // given
        $rawWithGroups = new LazyRawWithGroups($this->base());

        // when
        $keys = $rawWithGroups->getGroupKeys();

        // then
        $this->assertSame([0, 1, 2], $keys);
    }

    /**
     * @test
     */
    public function shouldCheckGroups()
    {
        // given
        $rawWithGroups = new LazyRawWithGroups($this->base());

        // when
        $hasGroup = $rawWithGroups->hasGroup(1);

        // then
        $this->assertTrue($hasGroup);
    }

    private function base(): Base
    {
        /*
         * This is supposed to cause catastrophic backtracking when used with preg_match_all(),
         * but find `123`, when used with preg_match()
         */
        return new ApiBase(Internal::pattern("(([a\d]+[a\d]+)+3)"), '  123 aaaaaaaaaaaaaaaaaaaa 3', new UserData());
    }
}

<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\_foreach\group;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Group\DetailGroup;
use TRegx\CleanRegex\Match\GroupLimit;

class GroupLimitTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGroupLimitBeIterable()
    {
        // given
        $result = [];

        // when
        /** @var DetailGroup $group */
        foreach ($this->matchGroup() as $group) {
            $result[] = [$group->text(), $group->usedIdentifier()];
        }

        // then
        $this->assertEquals([['cm', 1], ['mm', 1], ['m', 1]], $result);
    }

    private function matchGroup(): GroupLimit
    {
        return pattern('\d+([cm]?m)')->match('14cm 12mm 18m')->group(1);
    }
}

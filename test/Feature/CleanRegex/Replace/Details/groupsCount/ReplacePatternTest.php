<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\Details\groupsCount;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

/**
 * @coversNothing
 */
class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetGroupsCount()
    {
        // given
        pattern('(?<one>first) and (second)')
            ->replace('first and second')
            ->all()
            ->callback(function (Detail $detail) {
                // when
                $groupsCount = $detail->groupsCount();

                // then
                $this->assertSame(2, $groupsCount);

                // clean up
                return '';
            });
    }
}

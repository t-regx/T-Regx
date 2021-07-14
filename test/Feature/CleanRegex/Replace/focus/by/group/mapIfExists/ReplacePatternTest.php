<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\focus\by\group\mapIfExists;

use PHPUnit\Framework\TestCase;
use Test\Utils\CustomException;
use Test\Utils\FocusGroupPairs;
use TRegx\CleanRegex\Exception\FocusGroupNotMatchedException;

/**
 * @coversNothing
 */
class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReplace_focus_group_by_mapIfExists()
    {
        // given
        [$pattern, $subject] = FocusGroupPairs::patternAndSubject();

        // when
        $result = pattern($pattern)->replace($subject)->all()->focus('name')->by()->group('domain')->mapIfExists(['com' => 'GG'])->orElseWith('Foo');

        // then
        $this->assertSame('Links are http://GG.com/ and http://wikipedia.org http://Foo/ :)', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_focus_by_map()
    {
        // given
        [$pattern, $subject] = FocusGroupPairs::patternAndSubjectUnmatched();

        // then
        $this->expectException(FocusGroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to replace focused group 'name', but the group was not matched");

        // when
        pattern($pattern)->replace($subject)
            ->all()
            ->focus('name')
            ->by()
            ->group('domain')
            ->mapIfExists([])
            ->orElseThrow(CustomException::class);
    }
}

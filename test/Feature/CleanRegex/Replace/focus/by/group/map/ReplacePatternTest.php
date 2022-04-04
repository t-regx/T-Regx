<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\focus\by\group\map;

use PHPUnit\Framework\TestCase;
use Test\Utils\ExampleException;
use Test\Utils\FocusGroupPairs;
use TRegx\CleanRegex\Exception\FocusGroupNotMatchedException;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReplace_focus_group_by_map()
    {
        // given
        [$pattern, $subject] = FocusGroupPairs::patternAndSubject();
        $map = [
            'com' => 'GG',
            'org' => 'EZ',
        ];

        // when
        $result = pattern($pattern)->replace($subject)->all()->focus('name')->by()->group('domain')->map($map)->orElseWith('Foo');

        // then
        $this->assertSame('Links are http://GG.com/ and http://EZ.org http://Foo/ :)', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_focus_by_group_map()
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
            ->map([])
            ->orElseThrow(new ExampleException());
    }
}

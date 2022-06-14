<?php
namespace Test\Feature\CleanRegex\Replace\focus\by\group\mapAndCallback;

use PHPUnit\Framework\TestCase;
use Test\Utils\Classes\ExampleException;
use Test\Utils\Functions;
use Test\Utils\Values\FocusGroupPairs;
use TRegx\CleanRegex\Exception\FocusGroupNotMatchedException;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReplace_focus_group_by_mapAndCallback()
    {
        // given
        [$pattern, $subject] = FocusGroupPairs::patternAndSubject();
        $map = [
            'com' => 'four',
            'org' => 'sevennn',
        ];

        // when
        $result = pattern($pattern)->replace($subject)->all()
            ->focus('name')
            ->by()
            ->group('domain')
            ->mapAndCallback($map, Functions::singleArg('strLen'))
            ->orElseWith('Foo');

        // then
        $this->assertSame('Links are http://4.com/ and http://7.org http://Foo/ :)', $result);
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
            ->mapAndCallback([], Functions::fail())
            ->orElseThrow(new ExampleException());
    }
}

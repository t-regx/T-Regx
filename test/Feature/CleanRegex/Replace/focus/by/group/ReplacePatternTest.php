<?php
namespace Test\Feature\CleanRegex\Replace\focus\by\group;

use PHPUnit\Framework\TestCase;
use Test\Utils\Values\FocusGroupPairs;
use TRegx\CleanRegex\Match\Details\Detail;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReplace_focus_by_group_orElseIgnore()
    {
        // given
        [$pattern, $subject] = FocusGroupPairs::patternAndSubject();

        // when
        $result = pattern($pattern)->replace($subject)->all()->focus('name')->by()->group('domain')->orElseIgnore();

        // then
        $this->assertSame('Links are http://com.com/ and http://org.org http://localhost/ :)', $result);
    }

    /**
     * @test
     */
    public function shouldReplace_focus_by_group_orElseEmpty()
    {
        // given
        [$pattern, $subject] = FocusGroupPairs::patternAndSubject();

        // when
        $result = pattern($pattern)->replace($subject)->all()->focus('name')->by()->group('domain')->orElseEmpty();

        // then
        $this->assertSame('Links are http://com.com/ and http://org.org http:/// :)', $result);
    }

    /**
     * @test
     */
    public function shouldReplace_focus_by_group_orElseWith()
    {
        // given
        [$pattern, $subject] = FocusGroupPairs::patternAndSubject();

        // when
        $result = pattern($pattern)->replace($subject)->all()->focus('name')->by()->group('domain')->orElseWith('XX');

        // then
        $this->assertSame('Links are http://com.com/ and http://org.org http://XX/ :)', $result);
    }

    /**
     * @test
     */
    public function shouldReplace_focus_by_group_orElseCalling()
    {
        // given
        [$pattern, $subject] = FocusGroupPairs::patternAndSubject();

        // when
        $result = pattern($pattern)->replace($subject)->all()->focus('name')->by()->group('domain')->orElseCalling(function (Detail $detail) {
            $this->assertSame("http://localhost/", "$detail");
            return 'YY';
        });

        // then
        $this->assertSame('Links are http://com.com/ and http://org.org http://YY/ :)', $result);
    }
}

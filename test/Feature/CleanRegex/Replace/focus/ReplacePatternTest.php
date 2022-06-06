<?php
namespace Test\Feature\CleanRegex\Replace\focus;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\FocusGroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Match\Details\Detail;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReplace_focus_with()
    {
        // given
        [$pattern, $subject] = $this->patternAndSubject();

        // when
        $result = pattern($pattern)->replace($subject)->all()->focus('name')->with('xxx');

        // then
        $this->assertSame('Links: https://xxx.com and http://xxx.org. and again http://xxx.com', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_focus_with_onMissingGroup()
    {
        // given
        [$pattern, $subject] = $this->patternAndSubject();

        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        pattern($pattern)->replace($subject)->all()->focus('missing')->with('xxx');
    }

    /**
     * @test
     */
    public function shouldThrow_focus_with_onInvalidGroup()
    {
        // given
        [$pattern, $subject] = $this->patternAndSubject();

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '2foo' given");

        // when
        pattern($pattern)->replace($subject)->all()->focus('2foo')->with('xxx');
    }

    /**
     * @test
     */
    public function shouldThrow_focus_with()
    {
        // given
        [$pattern, $subject] = $this->patternAndSubjectUnmatched();

        // then
        $this->expectException(FocusGroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to replace focused group #1, but the group was not matched");

        // when
        pattern($pattern)->replace($subject)->all()->focus(1)->with('xxx');
    }

    /**
     * @test
     */
    public function shouldReplace_focus_callback()
    {
        // given
        [$pattern, $subject] = $this->patternAndSubject();

        // when
        $result = pattern($pattern)->replace($subject)->all()->focus('name')->callback(function (Detail $detail) {
            return "|{$detail->get('name')}|";
        });

        // then
        $this->assertSame('Links: https://|google|.com and http://|other|.org. and again http://|danon|.com', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_focus_callback()
    {
        // given
        [$pattern, $subject] = $this->patternAndSubjectUnmatched();

        // then
        $this->expectException(FocusGroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to replace focused group 'name', but the group was not matched");

        // when
        pattern($pattern)->replace($subject)->all()->focus('name')->callback(function (Detail $detail) {
            return "|{$detail->get('name')}|";
        });
    }

    /**
     * @test
     */
    public function shouldReplace_focus_withReferences()
    {
        // given
        [$pattern, $subject] = $this->patternAndSubject();

        // when
        $result = pattern($pattern)->replace($subject)->all()->focus('name')->withReferences(':\1|$1:');

        // then
        $this->assertSame('Links: https://:google|google:.com and http://:other|other:.org. and again http://:danon|danon:.com', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_focus_withReferences()
    {
        // given
        [$pattern, $subject] = $this->patternAndSubjectUnmatched();

        // then
        $this->expectException(FocusGroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to replace focused group 'name', but the group was not matched");

        // when
        pattern($pattern)->replace($subject)->all()->focus('name')->withReferences(':\1|$1:');
    }

    /**
     * @test
     */
    public function shouldReplace_focus_withReferences_0()
    {
        // given
        [$pattern, $subject] = $this->patternAndSubject();

        // when
        $result = pattern($pattern)->replace($subject)->all()->focus('name')->withReferences(':\0:');

        // then
        $this->assertSame('Links: https://:google:.com and http://:other:.org. and again http://:danon:.com', $result);
    }

    private function patternAndSubject(): array
    {
        return [
            'https?://(?<name>[a-z]+)\.(?<domain>com|org)',
            'Links: https://google.com and http://other.org. and again http://danon.com'
        ];
    }

    private function patternAndSubjectUnmatched(): array
    {
        return [
            'https?://(?<name>[a-z]+)?\.(?<domain>com|org)',
            'Links: https://google.com and http://.org.'
        ];
    }
}

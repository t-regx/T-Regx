<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\focus\by\group;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReplace_focus_by_group_orElseIgnore()
    {
        // given
        [$pattern, $subject] = $this->patternAndSubject();

        // when
        $result = pattern($pattern)->replace($subject)->all()->focus('name')->by()->group('domain')->orElseIgnore();

        // then
        $this->assertEquals('Links are http://www.com.com and http://org.org http://localhost :)', $result);
    }

    /**
     * @test
     */
    public function shouldReplace_focus_by_group_orElseEmpty()
    {
        // given
        [$pattern, $subject] = $this->patternAndSubject();

        // when
        $result = pattern($pattern)->replace($subject)->all()->focus('name')->by()->group('domain')->orElseEmpty();

        // then
        $this->assertEquals('Links are http://www.com.com and http://org.org http:// :)', $result);
    }

    /**
     * @test
     */
    public function shouldReplace_focus_by_group_orElseWith()
    {
        // given
        [$pattern, $subject] = $this->patternAndSubject();

        // when
        $result = pattern($pattern)->replace($subject)->all()->focus('name')->by()->group('domain')->orElseWith('XX');

        // then
        $this->assertEquals('Links are http://www.com.com and http://org.org http://XX :)', $result);
    }

    /**
     * @test
     */
    public function shouldReplace_focus_by_group_orElseCalling()
    {
        // given
        [$pattern, $subject] = $this->patternAndSubject();

        // when
        $result = pattern($pattern)->replace($subject)->all()->focus('name')->by()->group('domain')->orElseCalling(function (Detail $detail) {
            $this->assertEquals("http://localhost", "$detail");
            return 'YY';
        });

        // then
        $this->assertEquals('Links are http://www.com.com and http://org.org http://YY :)', $result);
    }

    private function patternAndSubject(): array
    {
        return [
            'https?://(?<name>[a-z]+)(?:\.(?<domain>com|org))?',
            'Links are http://google.com and http://wikipedia.org http://localhost :)'
        ];
    }
}

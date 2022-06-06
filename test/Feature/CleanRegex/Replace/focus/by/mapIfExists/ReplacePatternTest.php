<?php
namespace Test\Feature\CleanRegex\Replace\focus\by\mapIfExists;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\FocusGroupNotMatchedException;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReplace_focus_by_map()
    {
        // given
        [$pattern, $subject] = $this->patternAndSubject();
        $map = [
            'https://google.com' => 'GG',
            'http://other.org'   => 'EZ',
            'http://danon.com'   => 'DW'
        ];

        // when
        $result = pattern($pattern)->replace($subject)->all()->focus('name')->by()->mapIfExists($map);

        // then
        $this->assertSame('Links: https://GG.com and http://EZ.org. and again http://DW.com', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_focus_by_map()
    {
        // given
        [$pattern, $subject] = $this->patternAndSubjectUnmatched();

        // then
        $this->expectException(FocusGroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to replace focused group 'name', but the group was not matched");

        // when
        pattern($pattern)->replace($subject)->all()->focus('name')->by()->mapIfExists([]);
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

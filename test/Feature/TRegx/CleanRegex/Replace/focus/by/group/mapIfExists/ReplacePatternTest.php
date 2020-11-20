<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\focus\by\group\mapIfExists;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\FocusGroupNotMatchedException;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReplace_focus_group_by_map()
    {
        // given
        [$pattern, $subject] = $this->patternAndSubject();
        // when
        $result = pattern($pattern)->replace($subject)->all()
            ->focus('name')
            ->by()
            ->group('domain')
            ->mapIfExists(['com' => 'GG',])
            ->orElseThrow();

        // then
        $this->assertEquals('Links: https://GG.com and http://other.org. and again http://GG.com', $result);
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
        pattern($pattern)->replace($subject)
            ->all()
            ->focus('name')
            ->by()
            ->group('domain')
            ->mapIfExists([])
            ->orElseThrow();
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
            'Links: http://.org.'
        ];
    }
}

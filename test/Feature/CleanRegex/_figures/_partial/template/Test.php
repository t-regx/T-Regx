<?php
namespace Test\Feature\CleanRegex\_figures\_partial\template;

use PHPUnit\Framework\TestCase;
use Test\Utils\TestCase\TestCaseExactMessage;
use TRegx\CleanRegex\Pattern;

class Test extends TestCase
{
    use TestCaseExactMessage;

    /**
     * @test
     */
    public function shouldMatchOptionalPlaceholder()
    {
        // given
        $pattern = Pattern::template('^Foo:@?$')->literal('Bar');
        // when, then
        $this->assertTrue($pattern->test('Foo:Bar'), 'Failed to assert that placeholder was optional and present');
    }

    /**
     * @test
     */
    public function shouldMatchOptionalPlaceholderAbsent()
    {
        // given
        $pattern = Pattern::template('^Foo:@?$')->literal('Bar');
        // when, then
        $this->assertTrue($pattern->test('Foo:'), "Failed to assert that placeholder was optional and absent");
    }

    /**
     * @test
     */
    public function shouldNotMatchPartialOptionalPlaceholder()
    {
        // given
        $pattern = Pattern::template('^Foo:@?$')->literal('Bar');
        // when, then
        $this->assertTrue($pattern->fails('Foo:Ba'), "Failed to assert that partial of placeholder was matched");
    }
}

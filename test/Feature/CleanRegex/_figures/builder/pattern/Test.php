<?php
namespace Test\Feature\CleanRegex\_figures\builder\pattern;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldMatchOptionalPlaceholder()
    {
        // given
        $pattern = Pattern::builder('^Foo:@?$')->pattern('B[ab]r')->build();
        // when, then
        $this->assertTrue($pattern->test('Foo:Bar'), 'Failed to assert that placeholder was optional and present');
    }

    /**
     * @test
     */
    public function shouldMatchOptionalPlaceholderAbsent()
    {
        // given
        $pattern = Pattern::builder('^Foo:@?$')->pattern('Pattern')->build();
        // when, then
        $this->assertTrue($pattern->test('Foo:'), "Failed to assert that placeholder was optional and absent");
    }

    /**
     * @test
     */
    public function shouldNotMatchPartialOptionalPlaceholder()
    {
        // given
        $pattern = Pattern::builder('^Foo:@?$')->pattern('Bar')->build();
        // when, then
        $this->assertTrue($pattern->fails('Foo:Ba'), "Failed to assert that partial of placeholder was matched");
    }

    /**
     * @test
     */
    public function shouldNotApplyQuantifierBefore()
    {
        // given
        $pattern = Pattern::builder('^Foo:@?$')->pattern('')->build();
        // when, then
        $this->assertTrue($pattern->fails('Foo'), "Failed to assert that quantifier applied to placeholder");
    }

    /**
     * @test
     */
    public function shouldNotApplyQuantifierBeforeEmpty()
    {
        // given
        $pattern = Pattern::builder('^Foo:@?$')->pattern('')->build();
        // when, then
        $this->assertTrue($pattern->fails('Foo'), "Failed to assert that quantifier applied to placeholder");
    }
}

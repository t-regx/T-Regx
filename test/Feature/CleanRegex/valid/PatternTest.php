<?php
namespace Test\Feature\CleanRegex\valid;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

class PatternTest extends TestCase
{
    /**
     * @test
     * @dataProvider patterns
     * @param string $expression
     * @param bool $expected
     */
    public function testStandard(string $expression, bool $expected)
    {
        // given
        $pattern = Pattern::of($expression);
        // when then
        $this->assertSame($expected, $pattern->valid());
    }

    public function patterns(): array
    {
        return [
            'of'           => ['Foo', true],
            'pcre'         => ['/Foo/', true],
            'pcre,invalid' => ['/invalid)/', false],
            'invalid'      => ['invalid)', false],
            'empty'        => ['', true],
        ];
    }

    /**
     * @test
     */
    public function shouldAcceptTrailingBackslashControl()
    {
        // given
        $pattern = Pattern::of('\c\\');
        // when, then
        $this->assertTrue($pattern->valid());
    }

    /**
     * @test
     */
    public function shouldAcceptTrailingBackslashQuote()
    {
        // given
        $pattern = Pattern::of('\Q\\');
        // when, then
        $this->assertTrue($pattern->valid());
    }

    /**
     * @test
     */
    public function shouldAcceptTrailingBackslashComment()
    {
        // given
        $pattern = Pattern::of('#\\', 'x');
        // when, then
        $this->assertTrue($pattern->valid());
    }

    /**
     * @test
     */
    public function shouldNotAcceptTrailingBackslashCommentModeDisabled()
    {
        // given
        $pattern = Pattern::of('#\\');
        // when, then
        $this->assertFalse($pattern->valid());
    }

    /**
     * @test
     */
    public function shouldNotInfluenceFurtherChecks()
    {
        // when
        Pattern::of('/[a-')->valid();
        // when, then
        $this->assertTrue(Pattern::of('/[a-z]/')->valid());
    }

    /**
     * @test
     */
    public function shouldNotInterfereWithFurtherMatches()
    {
        try {
            Pattern::of('/[a-')->test('');
        } catch (MalformedPatternException $e) {
        }
        // when
        $valid = Pattern::of('[a-z]')->test('a');
        // then
        $this->assertTrue($valid);
    }
}

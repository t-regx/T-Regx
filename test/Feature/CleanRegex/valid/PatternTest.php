<?php
namespace Test\Feature\CleanRegex\valid;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;
use TRegx\CleanRegex\PcrePattern;
use TRegx\Exception\MalformedPatternException;

class PatternTest extends TestCase
{
    /**
     * @test
     * @dataProvider patterns
     * @param string $pattern
     * @param bool $expected
     * @param bool $_
     */
    public function testStandard(string $pattern, bool $expected, bool $_)
    {
        // given
        $pattern = Pattern::of($pattern);

        // when
        $valid = $pattern->valid();

        // then
        $this->assertSame($expected, $valid);
    }

    /**
     * @test
     * @dataProvider patterns
     * @param string $pattern
     * @param bool $_
     * @param bool $expected
     */
    public function testPcre(string $pattern, bool $_, bool $expected)
    {
        // given
        $pattern = PcrePattern::of($pattern);
        // when
        $valid = $pattern->valid();
        // then
        $this->assertSame($expected, $valid);
    }

    public function patterns(): array
    {
        return [
            'of'           => ['Foo', true, false],
            'pcre'         => ['/Foo/', true, true],
            'pcre,invalid' => ['/invalid)/', false, false],
            'invalid'      => ['invalid)', false, false],
            'empty'        => ['', true, false],
        ];
    }

    /**
     * @test
     */
    public function shouldAcceptTrailingBackslashControl()
    {
        // given
        $pattern = Pattern::of('\c\\');

        // when
        $valid = $pattern->valid();

        // then
        $this->assertTrue($valid);
    }

    /**
     * @test
     */
    public function shouldAcceptTrailingBackslashQuote()
    {
        // given
        $pattern = Pattern::of('\Q\\');

        // when
        $valid = $pattern->valid();

        // then
        $this->assertTrue($valid);
    }

    /**
     * @test
     */
    public function shouldAcceptTrailingBackslashComment()
    {
        // given
        $pattern = Pattern::of('#\\', 'x');

        // when
        $valid = $pattern->valid();

        // then
        $this->assertTrue($valid);
    }

    /**
     * @test
     */
    public function shouldNotAcceptTrailingBackslashCommentModeDisabled()
    {
        // given
        $pattern = Pattern::of('#\\');

        // when
        $valid = $pattern->valid();

        // then
        $this->assertFalse($valid);
    }

    /**
     * @test
     */
    public function shouldNotInfluenceFurtherChecks()
    {
        // when
        pattern('/[a-')->valid();
        // when
        $valid = pattern('/[a-z]/')->valid();
        // then
        $this->assertTrue($valid);
    }

    /**
     * @test
     */
    public function shouldNotInterfereWithFurtherMatches()
    {
        try {
            pattern('/[a-')->test('');
        } catch (MalformedPatternException $e) {
        }
        // when
        $valid = pattern('[a-z]')->test('a');
        // then
        $this->assertTrue($valid);
    }
}

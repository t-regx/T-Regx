<?php
namespace Test\Feature\TRegx\CleanRegex\Builder\PatternBuilder\mask;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsPattern;
use TRegx\CleanRegex\Pattern;

/**
 * @coversNothing
 */
class PatternBuilderTest extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     */
    public function shouldGet()
    {
        // when
        $pattern = Pattern::mask('(super):{%s.%d.%%}', [
            '%s' => '\s+',
            '%d' => '\d+',
            '%%' => '%'
        ]);

        // then
        $this->assertSamePattern('/\(super\)\:\{\s+\.\d+\.%\}/', $pattern);
    }

    /**
     * @test
     */
    public function shouldGet_WithFlags()
    {
        // when
        $pattern = Pattern::mask('My(super)pattern', [], 'ui');

        // then
        $this->assertSamePattern('/My\(super\)pattern/ui', $pattern);
    }
}

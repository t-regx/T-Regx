<?php
namespace Test\Feature\CleanRegex\_prepared\_alteration\_empty;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsPattern;
use TRegx\CleanRegex\Pattern;

class PatternTest extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     */
    public function shouldFailMatchingEmptyAlteration()
    {
        // when
        $pattern = Pattern::alteration([]);
        // then
        $this->assertPatternFails($pattern, 'Anything');
        $this->assertPatternFails($pattern, '');
        $this->assertPatternIs('/(*FAIL)/', $pattern);
    }
}

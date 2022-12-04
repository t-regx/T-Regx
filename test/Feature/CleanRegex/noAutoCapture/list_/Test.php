<?php
namespace Test\Feature\CleanRegex\noAutoCapture\list_;

use PHPUnit\Framework\TestCase;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Pattern;

class Test extends TestCase
{
    use TestCasePasses;

    /**
     * @test
     */
    public function testString()
    {
        // when, then
        $this->assertGroupMissing('(?n)(foo),(?<name>bar)');
    }

    /**
     * @test
     */
    public function testPattern()
    {
        // when, then
        $this->assertGroupMissing(Pattern::of('(?n)(foo),(?<name>bar)'));
    }

    private function assertGroupMissing($pattern): void
    {
        $replaced = Pattern::list([$pattern])->replace('"foo,bar"')->withReferences('$1');
        if ($replaced === '"bar"') {
            $this->pass();
        } else {
            $this->fail("Failed to assert that the first group was not capturing");
        }
    }
}

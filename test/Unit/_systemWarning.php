<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use function Test\Fixture\Functions\systemWarning;

class _systemWarning extends TestCase
{
    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function ignoreWarning()
    {
        systemWarning(fn() => new Pattern('word'));
    }
}

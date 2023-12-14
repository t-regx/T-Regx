<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use function Test\Fixture\Functions\catching;

class _pregLastError extends TestCase
{
    public function test()
    {
        catching(fn() => new Pattern(')'));
        $this->assertSame(\PREG_NO_ERROR, \preg_last_error());
    }
}

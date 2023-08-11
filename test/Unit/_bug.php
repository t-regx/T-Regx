<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;

class _bug extends TestCase
{
    /**
     * @link https://bugs.php.net/bug.php?id=78853
     */
    public function test()
    {
        $pattern = new Pattern('^|\d{1,2}$');
        $this->assertTrue($pattern->test('7'));
    }
}

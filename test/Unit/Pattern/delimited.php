<?php
namespace Test\Unit\Pattern;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;

class delimited extends TestCase
{
    public function test()
    {
        $pattern = new Pattern('\w+/\d+');
        $this->assertSame('#\w+/\d+#DX', $pattern->delimited());
    }

    /**
     * @test
     */
    public function duplicateModifiers()
    {
        $pattern = new Pattern('\w+', 'SDAxmixmxXDSA');
        $this->assertSame('/\w+/ADSXimx', $pattern->delimited());
    }
}

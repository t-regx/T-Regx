<?php
namespace Test\Functional\TRegx\SafeRegex\fatals;

use PHPUnit\Framework\TestCase;
use Test\DataProviders;
use TRegx\SafeRegex\preg;

class pregTest extends TestCase
{
    /**
     * @test
     * @dataProvider \Test\DataProviders::allPhpTypes
     * @param mixed $input
     */
    public function shouldNotThrowFatalErrors_forAnyPhpType_grep($input)
    {
        // when
        preg::grep('/./', [$input]);

        // then
        $this->assertTrue(true);
    }
}

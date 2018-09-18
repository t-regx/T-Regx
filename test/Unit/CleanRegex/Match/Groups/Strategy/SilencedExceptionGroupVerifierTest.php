<?php
namespace Test\Unit\CleanRegex\Match\Groups\Strategy;

use CleanRegex\Match\Groups\Strategy\SilencedExceptionGroupVerifier;
use PHPUnit\Framework\TestCase;

class SilencedExceptionGroupVerifierTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGroupExists()
    {
        // then
        $verifier = new SilencedExceptionGroupVerifier();

        // when
        $exists = $verifier->groupExists('', 'group');

        // then
        $this->assertTrue($exists);
    }
}

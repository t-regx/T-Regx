<?php
namespace Test\Unit\TRegx\CleanRegex\Match\Groups\Strategy;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Groups\Strategy\SilencedExceptionGroupVerifier;

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
        $exists = $verifier->groupExists('group');

        // then
        $this->assertTrue($exists);
    }
}

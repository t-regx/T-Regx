<?php
namespace Test\Utils\Runtime;

trait ExplicitStringEncoding
{
    public function setUp(): void
    {
        $encoding = \mb_internal_encoding();
        if ($encoding === 'UTF-8') {
            \mb_internal_encoding("7bit");
        } else {
            throw new \AssertionError("Failed to assert that internal encoding was set to UTF-8");
        }
    }

    public function tearDown(): void
    {
        $result = \mb_internal_encoding('UTF-8');
        if ($result === false) {
            throw new \AssertionError("Failed to assert that internal encoding was reset back to UTF-8");
        }
    }
}

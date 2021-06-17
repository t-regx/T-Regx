<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Template;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Prepared\Template\AlternationToken;

/**
 * @covers AlternationToken
 */
class AlternationTokenTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetType()
    {
        // given
        $token = new AlternationToken(['foo', 'bar', 'cat', 'door', 'fridge']);

        // when
        $type = $token->type();

        // then
        $this->assertSame('array (5)', $type);
    }
}

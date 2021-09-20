<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Template;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Prepared\Template\AlterationToken;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\Template\AlterationToken
 */
class AlterationTokenTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetType()
    {
        // given
        $token = new AlterationToken(['foo', 'bar', 'cat', 'door', 'fridge']);

        // when
        $type = $token->type();

        // then
        $this->assertSame('array (5)', "$type");
    }
}

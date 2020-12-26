<?php
namespace Test\Unit\TRegx\SafeRegex\Constants;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\Constants\PregMessages;

class PregMessagesTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetConstant()
    {
        // given
        $messages = new PregMessages();

        // when
        $constant = $messages->getConstant(PREG_BAD_UTF8_ERROR);

        // then
        $this->assertSame('Malformed UTF-8 characters, possibly incorrectly encoded', $constant);
    }

    /**
     * @test
     */
    public function shouldGetDefault()
    {
        // given
        $messages = new PregMessages();

        // when
        $constant = $messages->getConstant(101);

        // then
        $this->assertSame('Unknown error', $constant);
    }
}

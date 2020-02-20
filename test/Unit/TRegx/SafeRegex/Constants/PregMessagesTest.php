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
        $this->assertEquals('Malformed UTF-8 characters, possibly incorrectly encoded', $constant);
    }

    /**
     * @test
     */
    public function shouldGetDefault()
    {
        // given
        $unknown = 101;
        $messages = new PregMessages();

        // when
        $constant = $messages->getConstant($unknown);

        // then
        $this->assertEquals('Unknown error', $constant);
    }
}

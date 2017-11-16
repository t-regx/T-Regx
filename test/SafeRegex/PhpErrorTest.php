<?php
namespace Test\SafeRegex;

use SafeRegex\PhpError;
use PHPUnit\Framework\TestCase;

class PhpErrorTest extends TestCase
{
    public function testGetters()
    {
        // given
        $error = new PhpError(E_WARNING, 'Something failed', 'file.php', 12);

        // when
        $type = $error->getType();
        $message = $error->getMessage();
        $file = $error->getFile();
        $line = $error->getLine();

        // then
        $this->assertEquals(E_WARNING, $type);
        $this->assertEquals('Something failed', $message);
        $this->assertEquals('file.php', $file);
        $this->assertEquals(12, $line);
    }

    public function testStaticMethodFactory()
    {
        // given
        $array = [
            'type' => E_WARNING,
            'message' => 'Something failed',
            'file' => 'file.php',
            'line' => 12
        ];

        // when
        $error = PhpError::fromArray($array);

        // then
        $this->assertEquals(E_WARNING, $error->getType());
        $this->assertEquals('Something failed', $error->getMessage());
        $this->assertEquals('file.php', $error->getFile());
        $this->assertEquals(12, $error->getLine());
    }
}

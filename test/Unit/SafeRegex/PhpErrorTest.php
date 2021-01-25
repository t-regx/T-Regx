<?php
namespace Test\Unit\TRegx\SafeRegex;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\Internal\PhpError;

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
        $this->assertSame(E_WARNING, $type);
        $this->assertSame('Something failed', $message);
        $this->assertSame('file.php', $file);
        $this->assertSame(12, $line);
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
        $this->assertSame(E_WARNING, $error->getType());
        $this->assertSame('Something failed', $error->getMessage());
        $this->assertSame('file.php', $error->getFile());
        $this->assertSame(12, $error->getLine());
    }
}

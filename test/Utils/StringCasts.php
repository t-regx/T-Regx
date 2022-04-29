<?php
namespace Test\Utils;

trait StringCasts
{
    use PhpunitPolyfill;

    public abstract function expectExceptionMessage(string $message): void;

    public abstract function expectException(string $exception): void;

    public function expectExceptionCastsToString($object, string $className): void
    {
        $errorReporting = \error_reporting(E_ALL);
        $this->expectedCastToStringError($className);
        (string)$object;
        error_reporting($errorReporting);
    }

    private function expectedCastToStringError(string $className): void
    {
        if (PHP_VERSION_ID < 70400) {
            $this->expectError();
        } else {
            $this->expectException(\Error::class);
        }
        $this->expectExceptionMessage("Object of class $className could not be converted to string");
    }
}

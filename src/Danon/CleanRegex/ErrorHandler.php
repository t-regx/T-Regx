<?php
namespace Danon\CleanRegex;

class ErrorHandler
{
    public static function register()
    {
        set_error_handler([new ErrorHandler(), 'handler']);
    }

    public static function restoreOriginal()
    {
        restore_error_handler();
    }

    public function handler(int $code, string $message, string $file, int $line, array $context) {
        throw new PregException($message,$code, $line, $file);
    }
}

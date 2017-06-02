<?php
namespace Danon\CleanRegex;

class PregException extends \Exception
{
    public function __construct(string $message, int $code, int $line, string $file)
    {
        $this->message = $this->parseMessage($message);
        $this->line = $line;
        $this->file = $file;
        $this->code = $code;
    }

    private function parseMessage(string $message)
    {
        if (preg_match('~^preg_replace\(\): (.*)~', $message, $matches)) {
            return $matches[1];
        }

        if (preg_match('~^preg_replace_callback\(\): (.*)~', $message, $matches)) {
            return $matches[1];
        }

        return $message;
    }
}

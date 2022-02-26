<?php
namespace TRegx\CleanRegex\Internal\Delimiter;

use TRegx\CleanRegex\Exception\MalformedPcreTemplateException;

class PcreDelimiter
{
    /** @var string */
    public $delimiter;

    public function __construct(string $delimiter)
    {
        if ($this->valid($delimiter)) {
            $this->delimiter = $delimiter;
        } else {
            throw MalformedPcreTemplateException::invalidDelimiter($delimiter);
        }
    }

    private function valid(string $delimiter): bool
    {
        if (\in_array($delimiter, ["\0", "\t", "\n", "\v", "\f", "\r", ' ', "\\", '(', '[', '{', '<'], true)) {
            return false;
        }
        if (\ctype_alnum($delimiter)) {
            return false;
        }
        if (\ord($delimiter) > 127) {
            return false;
        }
        return true;
    }

    public function patternAndFlags(string $pcre): array
    {
        return $this->separatedAtPosition($pcre, $this->lastOccurrence($pcre));
    }

    private function separatedAtPosition(string $string, int $position): array
    {
        $before = \substr($string, 0, $position);
        $after = \substr($string, $position + 1);
        return [$before, $after];
    }

    private function lastOccurrence(string $pcre): int
    {
        $position = \strrpos($pcre, $this->delimiter);
        if ($position === false) {
            throw MalformedPcreTemplateException::unclosed($this->delimiter);
        }
        return $position;
    }
}

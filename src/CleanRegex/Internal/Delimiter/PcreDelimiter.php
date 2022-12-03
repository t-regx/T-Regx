<?php
namespace TRegx\CleanRegex\Internal\Delimiter;

use TRegx\CleanRegex\Exception\MalformedPcreTemplateException;

class PcreDelimiter
{
    /** @var string */
    public $delimiter;

    public function __construct(string $delimiter)
    {
        if ($this->legalDelimiter($delimiter)) {
            $this->delimiter = $delimiter;
        } else {
            throw new MalformedPcreTemplateException($this->malformedTemplateMessage($delimiter));
        }
    }

    private function malformedTemplateMessage(string $delimiter): string
    {
        if ($delimiter === "\0") {
            return 'null-byte delimiter';
        }
        if (\ctype_alnum($delimiter)) {
            return "alphanumeric delimiter '$delimiter'";
        }
        return "starting with an unexpected delimiter '$delimiter'";
    }

    private function legalDelimiter(string $delimiter): bool
    {
        if (\in_array($delimiter, ["\0", "\t", "\n", "\v", "\f", "\r", ' ', "\\", '(', '[', '{', '<'])) {
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
        return $this->separatedAtPosition($pcre, $this->closingDelimiterPosition($pcre));
    }

    private function separatedAtPosition(string $pcre, int $closingDelimiterPosition): array
    {
        $pattern = \subStr($pcre, 0, $closingDelimiterPosition);
        $modifiers = \subStr($pcre, $closingDelimiterPosition + 1);
        return [$pattern, $modifiers];
    }

    private function closingDelimiterPosition(string $pcre): int
    {
        $position = \strRPos($pcre, $this->delimiter);
        if ($position === false) {
            throw new MalformedPcreTemplateException("unclosed pattern '$this->delimiter'");
        }
        return $position;
    }
}

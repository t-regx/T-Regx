<?php
namespace TRegx\CleanRegex\Internal\Delimiter;

class PcreDelimiterPredicate
{
    public function test(string $delimiter): bool
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
}

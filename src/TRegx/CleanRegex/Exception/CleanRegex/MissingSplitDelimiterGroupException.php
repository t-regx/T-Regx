<?php
namespace TRegx\CleanRegex\Exception\CleanRegex;

class MissingSplitDelimiterGroupException extends CleanRegexException
{
    public const MESSAGE = "With preg_split(?, ?, PREG_SPLIT_DELIM_CAPTURE) - delimiter won't be captured, if it's not " .
    'contained in an explicit capturing group. Add an explicit capturing group to your delimiter, in order to include it. ' .
    'Use ex() instead, not to include the delimiter.';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}

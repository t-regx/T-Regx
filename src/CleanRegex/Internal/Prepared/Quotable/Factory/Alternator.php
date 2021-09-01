<?php
namespace TRegx\CleanRegex\Internal\Prepared\Quotable\Factory;

use TRegx\CleanRegex\Internal\Prepared\Quotable\UserInputQuotable;

class Alternator
{
    public static function quote(array $userInput, string $delimiter): string
    {
        return '(?:' . \implode('|', self::getQuoted($userInput, $delimiter)) . ')';
    }

    private static function getQuoted(array $userInput, string $delimiter): array
    {
        $result = [];
        foreach ($userInput as $input) {
            $result[] = (new UserInputQuotable($input))->quote($delimiter);
        }
        return $result;
    }
}

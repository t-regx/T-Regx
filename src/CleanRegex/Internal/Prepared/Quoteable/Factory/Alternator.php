<?php
namespace TRegx\CleanRegex\Internal\Prepared\Quoteable\Factory;

use TRegx\CleanRegex\Internal\Prepared\Quoteable\UserInputQuoteable;

class Alternator
{
    public static function quote(array $userInput, string $delimiter): string
    {
        return '(?:' . \implode('|', self::getQuoted($userInput, $delimiter)) . ')';
    }

    public static function quoteCapturing(array $userInput, string $delimiter): string
    {
        return '(' . \implode('|', self::getQuoted($userInput, $delimiter)) . ')';
    }

    private static function getQuoted(array $userInput, string $delimiter): array
    {
        $result = [];
        foreach ($userInput as $input) {
            $result[] = (new UserInputQuoteable($input))->quote($delimiter);
        }
        return $result;
    }
}

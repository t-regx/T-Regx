<?php
namespace Test\Utils\Values;

class FocusGroupPairs
{
    public static function patternAndSubject(): array
    {
        return [
            'https?://(?<name>[a-z]+)(?:\.(?<domain>com|org))?/?',
            'Links are http://google.com/ and http://wikipedia.org http://localhost/ :)'
        ];
    }

    public static function patternAndSubjectUnmatched(): array
    {
        return [
            'https?://(?<name>[a-z]+)?\.(?<domain>com|org)',
            'Links: http://.org.'
        ];
    }
}

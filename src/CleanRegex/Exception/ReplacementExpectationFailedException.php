<?php
namespace TRegx\CleanRegex\Exception;

class ReplacementExpectationFailedException extends \Exception implements PatternException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function insufficient(int $actual, int $expected, string $limitPhrase): self
    {
        return new self("Expected to perform $limitPhrase $expected replacement(s), but $actual replacement(s) were actually performed");
    }

    public static function superfluous(int $actual, int $expected, string $limitPhrase): self
    {
        return new self("Expected to perform $limitPhrase $expected replacement(s), but at least $actual replacement(s) would have been performed");
    }
}

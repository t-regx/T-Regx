<?php
namespace TRegx\CleanRegex\Exception;

class ReplacementExpectationFailedException extends \RuntimeException implements PatternException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function insufficient(int $actual, int $minimum, string $limitPhrase): self
    {
        return new self("Expected to perform $limitPhrase $minimum replacement(s), but $actual replacement(s) were actually performed");
    }

    public static function superfluous(int $maximum, string $limitPhrase): self
    {
        return new self("Expected to perform $limitPhrase $maximum replacement(s), but more than $maximum replacement(s) would have been performed");
    }
}

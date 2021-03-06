<?php
namespace TRegx\CleanRegex\Exception;

class ReplacementExpectationFailedException extends \Exception implements PatternException
{
    /** @var int */
    private $expected;
    /** @var int */
    private $replaced;

    public function __construct(string $message, int $expected, int $replaced)
    {
        parent::__construct($message);
        $this->expected = $expected;
        $this->replaced = $replaced;
    }

    public static function insufficient(int $actual, int $expected, string $limitPhrase): self
    {
        return new self("Expected to perform $limitPhrase $expected replacement(s), but $actual replacement(s) were actually performed", $expected, $actual);
    }

    public static function superfluous(int $actual, int $expected, string $limitPhrase): self
    {
        return new self("Expected to perform $limitPhrase $expected replacement(s), but at least $actual replacement(s) would have been performed", $expected, $actual);
    }

    public function getExpected(): int
    {
        return $this->expected;
    }

    public function getReplaced(): int
    {
        return $this->replaced;
    }
}

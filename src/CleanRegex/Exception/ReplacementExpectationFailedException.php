<?php
namespace TRegx\CleanRegex\Exception;

class ReplacementExpectationFailedException extends PatternException
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

    public static function insufficient(int $expected, int $replaced): self
    {
        return new self("Expected to perform exactly $expected replacement(s), but $replaced replacement(s) were actually performed", $expected, $replaced);
    }

    public static function superfluous(int $expected, int $replaced): self
    {
        return new self("Expected to perform exactly $expected replacement(s), but at least $replaced replacement(s) would have been performed", $expected, $replaced);
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

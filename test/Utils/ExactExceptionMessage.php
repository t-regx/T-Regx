<?php
namespace Test\Utils;

trait ExactExceptionMessage
{
    public function expectExceptionMessage(string $message): void
    {
        // I'm setting expectExceptionMessage(), so that when error is displayed to the
        // test runner, you will see string-to-string comparison, and not string-to-regex
        // comparison, which will be easier to read.
        parent::expectExceptionMessage($message);

        // I'm not using T-Regx here, but procedural pattern building, because if I ever
        // need this method to test pattern building, I don't want them to get
        // interfered with each other and produce false positives in tests.
        $this->phpUnitExpectExceptionMessage('/^' . \preg_quote($message, '/') . '$/');
    }

    private function phpUnitExpectExceptionMessage(string $string): void
    {
        if (\method_exists($this, 'expectExceptionMessageMatches')) {
            $this->expectExceptionMessageMatches($string);
        } else {
            $this->expectExceptionMessageRegExp($string);
        }
    }
}
